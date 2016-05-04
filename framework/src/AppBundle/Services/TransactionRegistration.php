<?php

namespace AppBundle\Services;

use AppBundle\Entity\Transaction;
use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityManager;
use Finance\Registration\RegistrationHandler;

/**
 * Class TransactionRegistration
 *
 * @package AppBundle\Services
 */
class TransactionRegistration
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * TransactionRegistration constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Register new Map Route
     *
     * @param CustomerInterface $customer
     * @param float $balance
     * @param int $operator
     * @param string $transactionId
     * @param string $cardId
     * @return Transaction
     */
    public function register(CustomerInterface $customer,
                             $balance,
                             $operator,
                             $transactionId,
                             $cardId)
    {
        $handler = new RegistrationHandler();
        $transaction = new Transaction($customer);
        $transaction = $handler->register($transaction,
            $balance,
            $operator,
            $transactionId,
            $cardId);
        $this->em->persist($transaction);
        $this->em->flush();
        return $transaction;
    }
}