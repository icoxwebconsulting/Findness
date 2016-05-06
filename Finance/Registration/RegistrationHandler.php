<?php

namespace Finance\Registration;

use Customer\Customer\CustomerInterface;
use Finance\Finance\FindnessOperator;
use Finance\Finance\PaypalOperator;
use Finance\Finance\StripeOperator;
use Finance\Finance\Transaction;
use Finance\Finance\TransactionInterface;

/**
 * Class RegistrationHandler
 *
 * @package Finance\Registration
 */
class RegistrationHandler
{

    /**
     * Get operator
     *
     * @param $operator
     * @return FindnessOperator|PaypalOperator|StripeOperator
     */
    private function getOperator($operator)
    {
        switch ($operator) {
            case 1: {
                return new FindnessOperator();
            }
            case 2: {
                return new PaypalOperator();
            }
            case 3: {
                return new StripeOperator();
            }
        }
    }

    /**
     * Handle customer registration logic
     *
     * @param TransactionInterface $transaction
     * @param float $balance
     * @param int $operator
     * @param string|null $transactionId
     * @param string|null $cardId
     * @param array|null $apisConf
     * @return TransactionInterface
     * @throws \Exception
     */
    public function register(TransactionInterface $transaction,
                             $balance,
                             $operator,
                             $transactionId = null,
                             $cardId = null,
                             array $apisConf)
    {
        $operator = $this->getOperator($operator);
        if ($operator->validateTransaction($transactionId, $apisConf[$operator->getName()])) {
            $transaction->setBalance($balance);
            $transaction->setOperator($operator);
            $transaction->setTransactionId($transactionId);
            $transaction->setCardId($cardId);
            return $transaction;
        }
    }

    /**
     * Charge Customer for search
     *
     * @param CustomerInterface $customer
     * @param int $itemsCount
     * @param float $fee
     * @param array|null $apisConf
     * @return TransactionInterface
     */
    public function charge(CustomerInterface $customer,
                           $itemsCount,
                           $fee,
                           array $apisConf)
    {
        $transaction = new Transaction($customer);
        $balance = -1 * ($itemsCount * $fee);
        return $this->register($transaction, $balance, (new FindnessOperator())->getId(), uniqid(),
            $customer->getId(), $apisConf);
    }
}