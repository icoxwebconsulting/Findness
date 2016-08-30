<?php

namespace AppBundle\Services;

use AppBundle\Entity\SharedStaticList;
use Customer\Customer\CustomerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Monolog\Logger;
use StaticList\Registration\RegistrationHandler;
use StaticList\StaticList\StaticListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class StaticList
 *
 * @package AppBundle\Services
 */
class StaticList
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $pushNotificationServices = array();

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Company constructor.
     *
     * @param EntityManager $em
     * @param array $pushNotificationServices
     */
    public function __construct(
        EntityManager $em,
        array $pushNotificationServices,
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        Logger $logger
    ) {
        $this->em = $em;
        $this->pushNotificationServices = $pushNotificationServices;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * @param CustomerInterface $customer
     * @param string $name
     * @param array $companies
     * @return StaticListInterface;
     */
    public function register(CustomerInterface $customer, $name, array $companies)
    {
        $companyEntities = [];
        foreach ($companies as $company) {
            try {
                $companyEntities[] = $this->em->createQueryBuilder()
                    ->select('c')
                    ->from('AppBundle:Company', 'c')
                    ->from('AppBundle:CustomerViewCompany', 'cvc')
                    ->where('c.id = cvc.company')
                    ->andWhere('cvc.customer = :customer')
                    ->andWhere('cvc.company = :company')
                    ->setParameter('customer', $customer->getId())
                    ->setParameter('company', $company)
                    ->getQuery()
                    ->getSingleResult();
            } catch (NoResultException $exception) {
                throw new HttpException(500, 'Company not found or does not belong to the user.');
            }
        }

        $register = new RegistrationHandler();
        $staticList = $register->register($customer, $name, $companyEntities);
        $staticList = \AppBundle\Entity\StaticList::fromBusinessEntity($staticList);

        try {
            $this->em->persist($staticList);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new HttpException(500, 'Not unique list name.');
        }

        return $staticList;
    }

    /**
     * Get static lists by customer
     *
     * @param CustomerInterface $customer
     * @return array
     */
    public function get(CustomerInterface $customer)
    {
        $staticLists = $this->em
            ->getRepository('AppBundle:StaticList')
            ->allByCustomer($customer);

        $response = [];

        foreach ($staticLists as $list) {
            $response[] = [
                "id" => $list->getId(),
                "name" => $list->getName(),
                "customer" => $list->getCustomer()->getId(),
            ];
        }

        return $response;
    }

    /**
     * @param CustomerInterface $receiver
     * @param $senderName
     * @param $staticListId
     * @param $staticListName
     */
    private function sendPushNotifications(CustomerInterface $receiver, $senderName, $staticListId, $staticListName)
    {
        $this->logger->info('Sending notifications');

        $devices = $this->em->getRepository('AppBundle:Device')->getByCustomer($receiver);
        if (count($devices)) {
            $this->logger->info(sprintf('%i devices for customer %s', count($devices), $receiver->getId()));
            foreach ($devices as $device) {
                if ($device && array_key_exists($device->getOS(), $this->pushNotificationServices)) {
                    $extra = ['type' => 2];
                    $extra['staticListId'] = $staticListId;
                    $title = 'Nueva Lista compartida';
                    $body = sprintf('%s le ha compartido la lista %s', $senderName, $staticListName);

                    $stats = null;

                    switch ($device->getOS()) {
                        case 'Android': {
                            $stats = $this->pushNotificationServices[$device->getOS()]->sendNotification(
                                [$device->getId()],
                                $title,
                                $body,
                                $extra,
                                null
                            );
                            break;
                        }
                        case 'IOS': {
                            $stats = $this->pushNotificationServices[$device->getOS()]->sendNotification(
                                [$device->getId()],
                                $title,
                                'com.thinkandcloud.findness',
                                'bingbong.aiff'
                            );
                        }
                    }

                    $delivered = $stats && !($stats['successful'] <= 0);
                    $logMessage = sprintf(
                        "Notification sent to %s: delivered = %s",
                        $device->getId(),
                        $delivered ? "true" : "false"
                    );
                    $this->logger->info($logMessage);
                }
            }
        }
    }

    /**
     * @param CustomerInterface $receiver
     * @param $senderName
     * @param $staticListName
     */
    private function sendEmail(CustomerInterface $receiver, $senderName, $staticListName)
    {
        $templateParameters = array(
            'receiverName' => $receiver->getFullName(),
            'senderName' => $senderName,
            'staticListName' => $staticListName,
        );

        $message = \Swift_Message::newInstance()
            ->setSubject('Nueva Lista compartida')
            ->setFrom('info@findness.com')
            ->setTo($receiver->getEmail())
            ->setBody(
                $this->twig->render(
                    'shared_list.html.twig',
                    $templateParameters
                ),
                'text/html'
            )
            ->addPart(
                $this->twig->render(
                    'shared_list.txt.twig',
                    $templateParameters
                ),
                'text/plain'
            );
        $this->mailer->send($message);
    }

    /**
     * @param CustomerInterface $receiver
     * @param $senderName
     * @param $staticListId
     * @param $staticListName
     */
    private function notify(CustomerInterface $receiver, $senderName, $staticListId, $staticListName)
    {
        $this->sendPushNotifications($receiver, $senderName, $staticListId, $staticListName);
        $this->sendEmail($receiver, $senderName, $staticListName);
    }

    /**
     * Share static list
     *
     * @param string $staticListId
     * @param CustomerInterface $owner
     * @param CustomerInterface $shared
     * @return bool
     */
    public function share($staticListId, CustomerInterface $owner, CustomerInterface $shared)
    {
        $staticList = $this->em
            ->getRepository('AppBundle:StaticList')
            ->find($staticListId);

        if (!$staticList) {
            throw new HttpException(500, 'La lista no existe.');
        }

        if ($staticList->getCustomer()->getId() !== $owner->getId()) {
            throw new HttpException(500, 'Usted no es el dueño.');
        }

        try {
            $register = new RegistrationHandler();
            $sharedStaticList = $register->share($shared, $staticList);
            $sharedStaticList = SharedStaticList::fromBusinessEntity($sharedStaticList);
        } catch (\Exception $exception) {
            throw new HttpException(500, $exception->getMessage());
        }

        try {
            $this->em->persist($sharedStaticList);
            $this->em->flush();

            $this->notify($shared, $owner->getUsername(), $staticListId, $staticList->getName());

        } catch (UniqueConstraintViolationException $exception) {
            throw new HttpException(500, 'La lista ya esta compartida con este usuario.');
        }

        return true;
    }

    /**
     * Get static list by customer (owner or shared)
     *
     * @param CustomerInterface $customer
     * @param string $staticListId
     * @return array
     */
    public function getStaticList(CustomerInterface $customer, $staticListId)
    {
        try {
            $sharedStaticList = $this->em
                ->getRepository('AppBundle:StaticList')
                ->byCustomer($customer, $staticListId);
        } catch (NoResultException $exception) {
            throw new HttpException(500, 'Static list not found.');
        }

        return $sharedStaticList->getCompanies();
    }

    /**
     * Delete static list by owner
     *
     * @param CustomerInterface $customer
     * @param string $staticListId
     * @return bool
     */
    public function deleteStaticList(CustomerInterface $customer, $staticListId)
    {
        try {
            $sharedStaticList = $this->em
                ->getRepository('AppBundle:SharedStaticList')
                ->byCustomer($customer, $staticListId);
        } catch (NoResultException $exception) {
            throw new HttpException(500, 'Static list not found.');
        }

        if ($sharedStaticList->getStaticList()->getCustomer()->getId() !== $customer->getId()) {
            throw new HttpException(500, 'Customer not owner.');
        }

        $this->em->remove($sharedStaticList);
        $this->em->remove($sharedStaticList->getStaticList());
        $this->em->flush();

        return true;
    }
}