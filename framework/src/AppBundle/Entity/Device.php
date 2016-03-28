<?php

namespace AppBundle\Entity;

use Customer\Customer\CustomerInterface;

class Device
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var integer
     */
    private $os;

    private static $availableOS = [
        "Android",
        "IOS"
    ];

    /**
     * @var CustomerInterface
     */
    private $customer;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $updated;

    /**
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * Device constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set OS
     *
     * @param $os
     * @return $this
     * @throws \Exception
     */
    public function setOS($os)
    {
        if (!in_array($os, Device::$availableOS)) {
            $exceptionMessage = sprintf('%s OS its not available, choose one of %s', $os, implode(', ', Device::$availableOS));
            throw new \Exception($exceptionMessage);
        }

        $this->os = $os;

        return $this;
    }

    /**
     * Get os
     *
     * @return integer
     */
    public function getOS()
    {
        return $this->os;
    }

    /**
     * Get customer
     *
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set customer
     *
     * @param CustomerInterface $customer
     * @return Device
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
}
