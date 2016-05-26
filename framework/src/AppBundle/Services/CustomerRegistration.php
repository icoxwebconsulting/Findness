<?php

namespace AppBundle\Services;

use AppBundle\Entity\Balance;
use Customer\Customer\CustomerInterface;
use Customer\Registration\RegistrationHandler;
use Doctrine\ORM\EntityManager;

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
     * CustomerRegistration constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
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
     * @return mixed
     */
    public function register(CustomerInterface $customer,
                             $username,
                             $firstName,
                             $lastName,
                             $salt,
                             $password)
    {
        $handler = new RegistrationHandler();
        $customer = $handler->register($customer,
            $username,
            $firstName,
            $lastName,
            $salt,
            $password);
        $this->em->persist($customer);
        $this->em->flush();
        $balance = new Balance($customer);
        $balance->setBalance(0);
        $this->em->persist($balance);
        $this->em->flush();
        return $customer;
    }

    /**
     * Register new customer
     *
     * @param CustomerInterface $customer
     * @param string $salt
     * @param string $password
     * @return mixed
     */
    public function changePassword(CustomerInterface $customer,
                                   $salt,
                                   $password)
    {
        $customer->setSalt($salt);
        $customer->setPassword($password);
        $this->em->flush();
        return $customer;
    }
}