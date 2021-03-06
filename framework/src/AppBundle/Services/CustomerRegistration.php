<?php

namespace AppBundle\Services;

use AppBundle\Entity\Balance;
use AppBundle\Entity\Subscription;
use AppBundle\Entity\Transaction;
use Customer\Customer\CustomerInterface;
use Customer\Registration\RegistrationHandler;
use Doctrine\ORM\EntityManager;
use Finance\Finance\FindnessOperator;

/**
 * Class CustomerRegistration
 *
 * @package AppBundle\Services
 */
class CustomerRegistration
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $templateEngine;

    /**
     * CustomerRegistration constructor.
     *
     * @param EntityManager $em
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templateEngine
     */
    public function __construct(EntityManager $em, \Swift_Mailer $mailer, \Twig_Environment $templateEngine)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
    }

    /**
     * Register new customer
     *
     * @param CustomerInterface $customer
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param string $salt
     * @param string $password
     * @return CustomerInterface|null
     */
    public function registerWithBalance(
        CustomerInterface $customer,
        $username,
        $firstName,
        $lastName,
        $salt,
        $password
    ) {
        try {
            $handler = new RegistrationHandler();
            $customer = $handler->register(
                $customer,
                $username,
                $firstName,
                $lastName,
                $salt,
                $password
            );
            $this->em->persist($customer);
            $this->em->flush();
            $balance = new Balance($customer);
            $balance->setBalance(10);
            $this->em->persist($balance);
            $this->em->flush();
            $this->sendRegistrationEmail($customer);

            return $customer;
        } catch (\Exception $exception) {
            return null;
        }

    }

    /**
     * @param CustomerInterface $customer
     */
    private function sendRegistrationEmail(CustomerInterface $customer)
    {
        $name = sprintf(
            "%s %s",
            $customer->getFirstName(),
            $customer->getLastName()
        );
        $confirmationToken = $customer->getConfirmationToken();

        $message = \Swift_Message::newInstance()
            ->setSubject('¡Te damos la bienvenida a Findness!')
            ->setFrom('info@findness.com', 'Findness Team')
            ->setTo($customer->getEmail())
            ->setBody(
                $this->templateEngine->render(
                    'registration_email.html.twig',
                    array(
                        'name' => $name,
                        'confirmationToken' => $confirmationToken,
                    )
                ),
                'text/html'
            )
            /*->addPart(
                $this->templateEngine->render(
                    'registration_email.txt.twig',
                    array(
                        'name' => $name,
                        'confirmationToken' => $confirmationToken,
                    )
                ),
                'text/plain'
            )*/;

       // $this->mailer->send($message);
    }

    /**
     * Register new customer
     *
     * @param CustomerInterface $customer
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param string $salt
     * @param string $password
     * @return CustomerInterface|null
     */
    public function register(
        CustomerInterface $customer,
        $username,
        $firstName,
        $lastName,
        $salt,
        $password
    ) {
        try {
            $handler = new RegistrationHandler();
            $customer = $handler->register(
                $customer,
                $username,
                $firstName,
                $lastName,
                $salt,
                $password
            );
            $this->em->persist($customer);
            $this->em->flush();

            $transaction = new Transaction($customer);
            $transaction->setBalance(0);
            $transaction->setOperator(new FindnessOperator());
            $transaction->setCardId('registration');
            $transaction->setTransactionId('registration');
            $this->em->persist($transaction);
            $this->em->flush();

            $subscription = new Subscription($customer, $transaction, Subscription::FREE_DAYS, new \DateTime());
            $this->em->persist($subscription);
            $this->em->flush();

            //$this->sendRegistrationEmail($customer);

            return $customer;
        } catch (\Exception $exception) {
            return null;
        }

    }

    /**
     * @param CustomerInterface $customer
     * @param $token
     * @return bool
     */
    public function confirm(CustomerInterface $customer, $token)
    {
        if ($customer->getConfirmationToken() == $token) {
            $customer->setConfirmed(true);
            $customer->setEnabled(true);
            $customer->setConfirmationToken(null);
            $this->em->flush();

            return true;
        }

        return false;
    }

    /**
     * Register new password customer
     *
     * @param CustomerInterface $customer
     * @param string $code
     * @param string $password
     * @param string $salt
     * @return mixed
     */
    public function changeNewPassword(
        CustomerInterface $customer,
        $code,
        $password,
        $salt
    ) {
        if ($customer->getSecurityCode() == $code) {
            $this->changePassword($customer, $salt, $password);

            return true;
        }

        return false;
    }

    /**
     * Register new customer
     *
     * @param CustomerInterface $customer
     * @param string $salt
     * @param string $password
     * @return mixed
     */
    public function changePassword(
        CustomerInterface $customer,
        $salt,
        $password
    ) {
        $customer->setSalt($salt);
        $customer->setPassword($password);
        $customer->setSecurityCode(null);
        $this->em->flush();

        return $customer;
    }

    /**
     * Reset password customer
     *
     * @param CustomerInterface $customer
     * @return mixed
     */
    public function resetPassword(CustomerInterface $customer)
    {
        $code = $this->generateSecurityCode();
        $customer->setSecurityCode($code);
        $this->em->flush();

        $message = \Swift_Message::newInstance()
            ->setSubject('Código de seguridad')
            ->setFrom('info@findness.com')
            ->setTo($customer->getEmail())
            ->setBody(
                'Su código de seguridad de Findness es: '.$code,
                'text/html'
            );

        $response = $this->mailer->send($message);

        return $response !== 0;
    }

    /**
     * @param int $length
     * @return string
     */
    private function generateSecurityCode($length = 6)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    /**
     * Resend Confirmation Email
     *
     * @param CustomerInterface $customer
     * @return bool
     */
    public function resendConfirmationEmail(CustomerInterface $customer)
    {
        try {
            $customer->setEnabled(false);
            $customer->setConfirmationToken(mt_rand(100000, 999999));
            $customer->setConfirmed(false);

            $this->em->flush();

            $this->sendRegistrationEmail($customer);

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Update customer profile
     *
     * @param CustomerInterface $customer
     * @param $firstName
     * @param $lastName
     * @return bool
     */
    public function updateProfile(CustomerInterface $customer, $firstName, $lastName)
    {
        $customer->setFirstName($firstName);
        $customer->setLastName($lastName);
        $this->em->flush();

        return true;
    }
}
