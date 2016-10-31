<?php

namespace AppBundle\Services;

use AppBundle\Entity\Subscription;
use AppBundle\Entity\Transaction;
use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityManager;
use Finance\Finance\BalanceInterface;
use Finance\Registration\RegistrationHandler;

/**
 * Class TransactionRegistration
 *
 * @package AppBundle\Services
 */
class TransactionRegistration
{

    /**
     * @var array
     */
    private $apiConfig;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * TransactionRegistration constructor.
     *
     * @param string $stripeKey
     * @param string $stripeSecret
     * @param string $paypalClientId
     * @param string $paypalSecret
     * @param EntityManager $em
     */
    public function __construct($stripeKey, $stripeSecret, $paypalClientId, $paypalSecret, EntityManager $em)
    {
        $this->apiConfig = [
            "stripe" => [
                "key" => $stripeKey,
                "secret" => $stripeSecret,
            ],
            "paypal" => [
                "id" => $paypalClientId,
                "secret" => $paypalSecret,
            ],
            "findness" => [

            ],
        ];
        $this->em = $em;
    }

    /**
     * Register new transaction
     *
     * @param CustomerInterface $customer
     * @param float $balance
     * @param int $operator
     * @param string $transactionId
     * @param string $cardId
     * @return Transaction
     * @throws \Exception
     */
    public function register(
        CustomerInterface $customer,
        $balance,
        $operator,
        $transactionId,
        $cardId
    ) {
        $transaction = $this->em->getRepository("AppBundle:Transaction")
            ->findOneBy(
                [
                    "transactionId" => $transactionId,
                ]
            );

        if ($transaction) {
            throw new \Exception("Transaction already exist");
        }

        return $this->create($customer, $balance, $operator, $transactionId, $cardId);
    }

    /**
     * Create transaction
     *
     * @param CustomerInterface $customer
     * @param $balance
     * @param $operator
     * @param $transactionId
     * @param $cardId
     * @return Transaction|\Finance\Finance\TransactionInterface
     */
    private function create(
        CustomerInterface $customer,
        $balance,
        $operator,
        $transactionId,
        $cardId
    ) {
        $handler = new RegistrationHandler();
        $transaction = new Transaction($customer);
        $transaction = $handler->register(
            $transaction,
            $balance,
            $operator,
            $transactionId,
            $cardId,
            $this->apiConfig
        );
        $this->em->persist($transaction);
        $this->em->flush();

        $balanceEntity = $this->getBalance($customer);
        $balanceEntity->setBalance($balanceEntity->getBalance() + $transaction->getBalance());
        $this->em->flush();

        return $transaction;
    }

    /**
     * Get customer balance
     *
     * @param CustomerInterface $customer
     * @return BalanceInterface
     */
    private function getBalance(CustomerInterface $customer)
    {
        return $this->em->getRepository("AppBundle:Balance")->findOneByCustomer($customer);
    }

    /**
     * Subscribe
     *
     * @param CustomerInterface $customer
     * @param float $balance
     * @param int $operator
     * @param string $transactionId
     * @param string $cardId
     * @param string $lapse
     * @param \DateTime $startDate
     * @return Subscription
     * @throws \Exception
     */
    public function subscribe(
        CustomerInterface $customer,
        $balance,
        $operator,
        $transactionId,
        $cardId,
        $lapse,
        $startDate
    ) {
        if ($this->em->getRepository("AppBundle:Transaction")
            ->findOneBy(
                [
                    "transactionId" => $transactionId,
                ]
            )
        ) {
            throw new \Exception("Transaction already exist");
        }

        if ($this->em->getRepository("AppBundle:Subscription")
            ->findOneBy(
                [
                    "startDate" => $startDate,
                ]
            )
        ) {
            throw new \Exception("Subscription for this period already enabled");
        }

        $handler = new RegistrationHandler();

        $transaction = new Transaction($customer);
        $transaction = $handler->register(
            $transaction,
            $balance,
            $operator,
            $transactionId,
            $cardId,
            $this->apiConfig
        );

        $subscription = $handler->subscribe(
            $transaction,
            $balance,
            $operator,
            $transactionId,
            $cardId,
            $this->apiConfig,
            $lapse,
            $startDate
        );

        $this->em->persist($transaction);
        $this->em->persist($subscription);
        $this->em->flush();

        return $subscription;
    }
}