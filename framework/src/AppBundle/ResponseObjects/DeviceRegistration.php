<?php

namespace AppBundle\ResponseObjects;

use AppBundle\Entity\Device;
use Customer\Customer\CustomerInterface;

/**
 * Class DeviceRegistration
 *
 * @package AppBundle\ResponseObjects
 */
class DeviceRegistration
{

    /**
     * @var string
     */
    public $customer;

    /**
     * @var string
     */
    public $device;

    /**
     * DeviceRegistration constructor.
     *
     * @param CustomerInterface $customer
     * @param Device $device
     */
    public function __construct(CustomerInterface $customer, Device $device)
    {
        $this->customer = $customer->getId();
        $this->device = $device->getId();
    }
}