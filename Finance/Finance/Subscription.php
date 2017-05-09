<?php

namespace Finance\Finance;

use Customer\Customer\CustomerInterface;

/**
 * Class Subscription
 *
 * @package Finance\Finance
 */
class Subscription implements SubscriptionInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $lapse;

    /**
     * @var \DateTime
     */
    protected $startDate;

    /**
     * @var \DateTime
     */
    protected $endDate;

    /**
     * @var TransactionInterface
     */
    protected $transaction;

    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * Subscription constructor.
     *
     * @param CustomerInterface $customer
     * @param TransactionInterface $transaction
     * @param $lapse
     * @param \DateTime|null $startDate
     */
    public function __construct(
        CustomerInterface $customer,
        TransactionInterface $transaction,
        $lapse,
        $startDate = null
    ) {
        self::validateLapse($lapse);
        $this->lapse = $lapse;

        if ($startDate) {
            $this->startDate = $startDate;
        } else {
            $this->startDate = new \DateTime();
        }

        $this->endDate = clone $this->startDate;
        if ($this->lapse == 0){
            $this->endDate->add(new \DateInterval('P30D'));

        }else{

            $this->endDate->add(new \DateInterval(sprintf("P%dM", $this->lapse)));
        }

        $this->customer = $customer;
        $this->transaction = $transaction;

        $this->id = uniqid();
    }

    /**
     * @inheritdoc
     */
    static public function validateLapse($lapse)
    {
        if (!in_array($lapse, array_keys(self::LAPSES))) {
            throw new \Exception('Lapse not available.');
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
     * @inheritDoc
     */
    public function getLapse()
    {
        return $this->lapse;
    }

    /**
     * @inheritDoc
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @inheritDoc
     */
    public function getEndDate()
    {
        return $this->endDate;
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
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @inheritDoc
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}