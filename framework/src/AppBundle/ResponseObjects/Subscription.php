<?php

namespace AppBundle\ResponseObjects;

use Finance\Finance\SubscriptionInterface;

/**
 * Class Subscription
 *
 * @package AppBundle\ResponseObjects
 */
class Subscription
{
    /**
     * @var string
     */
    public $id;

    /**
     * Transaction constructor.
     *
     * @param SubscriptionInterface $subscription
     */
    public function __construct(SubscriptionInterface $subscription)
    {
        $this->id = $subscription->getId();
    }
}