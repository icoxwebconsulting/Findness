<?php

namespace Finance\Finance;

use Customer\Customer\CustomerDependantInterface;

/**
 * Interface Balance
 *
 * @package Finance\Finance
 */
interface BalanceInterface extends CustomerDependantInterface
{
    /**
     * Get Balance
     *
     * @return float
     */
    public function getBalance();

    /**
     * Set balance
     *
     * @param float $balance
     */
    public function setBalance($balance);
}