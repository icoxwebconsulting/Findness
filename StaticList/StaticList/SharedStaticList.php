<?php

namespace StaticList\StaticList;

use Customer\Customer\CustomerInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class SharedStaticList
 *
 * @package StaticList\StaticList
 */
class SharedStaticList implements SharedStaticListInterface
{
    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @var StaticListInterface
     */
    protected $staticList;

    /**
     * SharedStaticList constructor.
     *
     * @param CustomerInterface $customer
     * @param StaticListInterface $staticList
     * @throws \Exception
     */
    public function __construct(CustomerInterface $customer, StaticListInterface $staticList)
    {
        if ($customer->getId() === $staticList->getCustomer()->getId()) {
            throw new \Exception('Cant share list with owner.');
        }

        $this->customer = $customer;
        $this->staticList = $staticList;
    }

    /**
     * @inheritDoc
     */
    public function setCustomer(CustomerInterface $customer)
    {
        if ($customer->getId() === $this->staticList->getCustomer()->getId()) {
            throw new \Exception('Cant share list with owner.');
        }

        $this->customer = $customer;
    }

    /**
     * @inheritDoc
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @inheritDoc
     */
    public function setStaticList(StaticListInterface $staticList)
    {
        if ($staticList->getCustomer()->getId() === $this->customer->getId()) {
            throw new \Exception('Cant share list with owner.');
        }

        $this->staticList = $staticList;
    }

    /**
     * @inheritDoc
     */
    public function getStaticList()
    {
        return $this->staticList;
    }
}