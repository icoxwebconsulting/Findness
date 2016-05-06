<?php

namespace Finance\Finance;

use Customer\Customer\CustomerDependantInterface;

/**
 * Interface Transaction
 *
 * @package Finance\Finance
 */
interface TransactionInterface extends CustomerDependantInterface
{
    /**
     * Get Customer id
     *
     * @return string
     */
    public function getId();

    /**
     * Get Balance
     *
     * @return float
     */
    public function getBalance();

    /**
     * Set Balance
     *
     * @param float $balance
     */
    public function setBalance($balance);

    /**
     * Get Operator
     *
     * @return OperatorInterface
     */
    public function getOperator();

    /**
     * Set Operator
     *
     * @param OperatorInterface $operator
     */
    public function setOperator(OperatorInterface $operator);

    /**
     * Get External Transaction Id
     *
     * @return string
     */
    public function getTransactionId();

    /**
     * Set External Transaction Id
     *
     * @param string $transactionId
     */
    public function setTransactionId($transactionId);

    /**
     * Get card id
     *
     * @return string
     */
    public function getCardId();

    /**
     * Set Card Id
     *
     * @param string $cardId
     */
    public function setCardId($cardId);
}