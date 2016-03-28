<?php

namespace AppBundle\Services;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Device;
use Customer\Customer\CustomerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

/**
 * Class DeviceRegistration
 *
 * @package AppBundle\Services
 */
class DeviceRegistration
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
     * @param Device $device
     * @param CustomerInterface $customer
     * @return Customer
     */
    public function register(Device $device, CustomerInterface $customer)
    {
        $device->setCustomer($customer);
        $this->em->persist($device);

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            return null;
        }

        return [
            'customer' => $customer,
            'device' => $device
        ];
    }

    /**
     * Un-Register a device
     *
     * @param Device $device
     * @return bool
     */
    public function unRegister(Device $device)
    {
        $this->em->remove($device);
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}