<?php

namespace AppBundle\Entity;

use Customer\Customer\CustomerInterface;
use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;

/**
 * Class AuthCode
 *
 * @package AppBundle\Entity
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var CustomerInterface
     */
    protected $customer;

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
     * AuthCode constructor.
     * @param string|null $id
     * @param Client|null $client
     * @param CustomerInterface|null $customer
     */
    public function __construct($id = null, Client $client = null, CustomerInterface $customer = null)
    {
        if ($id) {
            $this->id = $id;
        } else {
            $this->id = uniqid();
        }

        if ($client) {
            $this->client = $client;
        }

        if ($customer) {
            $this->customer = $customer;
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Client|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return CustomerInterface|null
     */
    public function getCustomer()
    {
        return $this->customer;
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