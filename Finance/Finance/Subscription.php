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
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @inheritdoc
     */
    static public function validateLapse($lapse)
    {
        if (!in_array($lapse, self::LAPSES)) {
            throw new \Exception('Lapse not available.');
        }
    }

    /**
     * Subscription constructor.
     *
     * @param CustomerInterface $customer
     * @param $lapse
     * @param \DateTime|null $startDate
     */
    public function __construct(CustomerInterface $customer, $lapse, $startDate = null)
    {
        self::validateLapse($lapse);
        $this->lapse = $lapse;

        if ($startDate) {
            $this->startDate = $startDate;
        } else {
            $this->startDate = new \DateTime();
        }

        $this->endDate = clone $this->startDate;
        $this->endDate->add(new \DateInterval(sprintf("P%dM", $this->lapse)));

        $this->customer = $customer;
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
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @inheritDoc
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}