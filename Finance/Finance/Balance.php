<?php

namespace Finance\Finance;

use Customer\Customer\CustomerInterface;

/**
 * Class Balance
 *
 * @package Finance\Finance
 */
class Balance implements BalanceInterface
{

    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @var float
     */
    protected $balance;

    /**
     * Balance constructor.
     * @param CustomerInterface $customer
     */
    public function __construct(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @inheritdoc
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @inheritdoc
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @inheritdoc
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @inheritdoc
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }
}