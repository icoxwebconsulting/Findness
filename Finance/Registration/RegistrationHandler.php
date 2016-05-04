<?php

namespace Finance\Registration;

use Finance\Finance\FindnessOperator;
use Finance\Finance\OperatorInterface;
use Finance\Finance\PaypalOperator;
use Finance\Finance\StripeOperator;
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

    private function getReference(OperatorInterface $operator, $transactionId = null, $cardId = null)
    {
        if ($operator instanceof FindnessOperator) {
            return uniqid();
        } else {
            if (!$transactionId || !$cardId) {
                throw new \Exception("Need to provide transaction id and card id");
            }
            return sprintf("%s@%s", $transactionId, $cardId);
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
     * @return TransactionInterface
     */
    public function register(TransactionInterface $transaction,
                             $balance,
                             $operator,
                             $transactionId = null,
                             $cardId = null)
    {
        $transaction->setBalance($balance);
        $operator = $this->getOperator($operator);
        $transaction->setOperator($operator);
        $reference = $this->getReference($operator, $transactionId, $cardId);
        $transaction->setReference($reference);
        return $transaction;
    }
}