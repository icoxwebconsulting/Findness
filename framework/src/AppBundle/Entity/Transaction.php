<?php

namespace AppBundle\Entity;

use Finance\Finance\FindnessOperator;
use Finance\Finance\PaypalOperator;
use Finance\Finance\StripeOperator;
use Finance\Finance\Transaction as TransactionBase;

/**
 * Transaction ORM Entity
 *
 * @package AppBundle\Entity
 */
class Transaction extends TransactionBase
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
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * PrePersist and PreUpdate convert the operator object to integer
     */
    public function convertOperatorToInteger()
    {
        $this->operator = $this->operator->getId();
    }

    /**
     * PostPersist, PostUpdate and PostLoad convert the operator from integer
     */
    public function convertOperatorFromInteger()
    {
        switch ($this->operator) {
            case 1: {
                $this->operator = new FindnessOperator();
                break;
            }
            case 2: {
                $this->operator = new PaypalOperator();
                break;
            }
            case 3: {
                $this->operator = new StripeOperator();
                break;
            }
        }
    }
}
