<?php

namespace AppBundle\Services;

use AppBundle\Entity\SharedStaticList;
use Customer\Customer\CustomerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
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
     * Company constructor.
     *
     * @param EntityManager $em
     * @param array $pushNotificationServices
     */
    public function __construct(EntityManager $em, array $pushNotificationServices)
    {
        $this->em = $em;
        $this->pushNotificationServices = $pushNotificationServices;
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
                "name" => $list->getName()
            ];
        }

        return $response;
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
        try {
            $staticList = $this->em
                ->getRepository('AppBundle:StaticList')
                ->findOneBy([
                    "id" => $staticListId,
                    "customer" => $owner->getId()
                ]);
        } catch (NoResultException $exception) {
            throw new HttpException(500, 'Static list not found.');
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

            $devices = $this->em->getRepository('AppBundle:Device')->getByCustomer($shared);
            if (count($devices)) {
                foreach ($devices as $device) {
                    if ($device && array_key_exists($device->getOS(), $this->pushNotificationServices)) {
                        $extra = ['type' => 2];
                        $extra['staticListId'] = $staticList;
                        $title = 'Nueva Lista compartida';
                        $body = sprintf('%s le ha compartido la lista %s', $owner->getUsername(), $staticList->getName());

                        switch ($device->getOS()) {
                            case 'Android': {
                                $this->pushNotificationServices[$device->getOS()]->sendNotification(
                                    [$device->getId()],
                                    $title,
                                    $body,
                                    $extra,
                                    null);
                                break;
                            }
                            case 'IOS': {
                                $this->pushNotificationServices[$device->getOS()]->sendNotification(
                                    [$device->getId()],
                                    $title,
                                    'com.thinkandcloud.findness',
                                    'bingbong.aiff');
                            }
                        }
                    }
                }
            }
        } catch (UniqueConstraintViolationException $exception) {
            throw new HttpException(500, 'Static list already shared with this customer.');
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