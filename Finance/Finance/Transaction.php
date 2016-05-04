<?php

namespace Finance\Finance;

use Customer\Customer\CustomerInterface;

/**
 * Class Balance
 *
 * @package Finance\Finance
 */
class Transaction implements TransactionInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @var float
     */
    protected $balance;

    /**
     * @var OperatorInterface
     */
    protected $operator;

    /**
     * @var string
     */
    protected $reference;

    /**
     * Balance constructor.
     *
     * @param CustomerInterface $customer
     * @param string|null $id
     */
    public function __construct(CustomerInterface $customer, $id = null)
    {
        $this->customer = $customer;

        $this->id = $id;
        if (!$this->id) {
            $this->id = uniqid();
        }
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @inheritdoc
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @inheritdoc
     */
    public function setOperator(OperatorInterface $operator)
    {
        $this->operator = $operator;
    }

    /**
     * @inheritdoc
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @inheritdoc
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }
}