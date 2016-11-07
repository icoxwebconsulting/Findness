<?php

namespace Finance\Finance;

use Customer\Customer\CustomerDependantInterface;

/**
 * Interface SubscriptionInterface
 *
 * @package Finance\Finance
 */
interface SubscriptionInterface extends CustomerDependantInterface
{
    const ONE_MONTH = 1;
    const SIX_MONTHS = 6;
    const ONE_YEAR = 12;

    const LAPSES = array(
        self::ONE_MONTH => "-1000",
        self::SIX_MONTHS => "12",
        self::ONE_YEAR => "22",
    );

    /**
     * @param $lapse
     * @return bool
     * @throws \Exception
     */
    static public function validateLapse($lapse);

    /**
     * Get Customer id
     *
     * @return string
     */
    public function getId();

    /**
     * @return int
     */
    public function getLapse();

    /**
     * @return \DateTime
     */
    public function getStartDate();

    /**
     * @return \DateTime
     */
    public function getEndDate();

    /**
     * @return TransactionInterface
     */
    public function getTransaction();
}