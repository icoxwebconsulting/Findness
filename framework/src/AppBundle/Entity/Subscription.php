<?php

namespace AppBundle\Entity;

use Finance\Finance\Subscription as SubscriptionBase;
use Finance\Finance\SubscriptionInterface;

/**
 * Class Subscription
 *
 * @package AppBundle\Entity
 */
class Subscription extends SubscriptionBase
{
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
     * Get orm transaction from business entity
     *
     * @param SubscriptionInterface $subscription
     * @return Subscription
     */
    static public function fromBusinessEntity(SubscriptionInterface $subscription)
    {
        $ormTransaction = new Subscription(
            $subscription->getCustomer(),
            $subscription->getTransaction(),
            $subscription->getLapse(),
            $subscription->getStartDate()
        );

        return $ormTransaction;
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
