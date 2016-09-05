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
        if ($operator->validateTransaction($transactionId, $balance, $apisConf[$operator->getName()])) {
            $transaction->setBalance($balance);
            $transaction->setOperator($operator);
            $transaction->setTransactionId($transactionId);
            $transaction->setCardId($cardId);
            return $transaction;
        }
    }

    public function computeBalance($itemsCount,
                                   $fee,
                                   $min,
                                   $extraFee,
                                   $extraFeeThreshold,
                                   $ivaFee,
                                   $currentBalance)
    {
        if ($itemsCount) {
            $balance = $itemsCount * $fee;

            // validate business restriction of minimum fee
            if ((float)$balance < (float)$min) {
                throw new \Exception('Minimum fee not reached.');
            }

            // apply extra fee if needed
            if ((float)$balance < (float)$extraFeeThreshold) {
                $balance = $balance + $extraFee;
            }

            // apply iva
            $balance = (float)number_format($balance + ($balance * ($ivaFee / 100)), 2);

            if ((float)$balance > (float)$currentBalance) {
                throw new \Exception('Saldo insuficiente.');
            }

            // set to negative
            return -1 * $balance;
        }
    }

    /**
     * Charge Customer for search
     *
     * @param CustomerInterface $customer
     * @param $balance
     * @param array $apisConf
     * @return TransactionInterface
     * @throws \Exception
     */
    public function charge(CustomerInterface $customer,
                           $balance,
                           array $apisConf)
    {
        $transaction = new Transaction($customer);
        return $this->register($transaction, $balance, (new FindnessOperator())->getId(), uniqid(),
            $customer->getId(), $apisConf);
    }
}