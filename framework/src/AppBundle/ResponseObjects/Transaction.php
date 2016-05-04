<?php

namespace AppBundle\ResponseObjects;

use Finance\Finance\TransactionInterface;

/**
 * Class Transaction
 *
 * @package AppBundle\ResponseObjects
 */
class Transaction
{
    /**
     * @var string
     */
    public $id;

    /**
     * Transaction constructor.
     *
     * @param TransactionInterface $transaction
     */
    public function __construct(TransactionInterface $transaction)
    {
        $this->id = $transaction->getId();
    }
}