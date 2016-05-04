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
     * Get Reference
     *
     * @return string
     */
    public function getReference();

    /**
     * Set Reference
     *
     * @param string $reference
     */
    public function setReference($reference);
}