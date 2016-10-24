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
    const SIX_MONTHS = 6;
    const ONE_YEAR = 12;
    const LAPSES = array(
        self::SIX_MONTHS,
        self::ONE_YEAR,
    );

    /**
     * @param $lapse
     * @return bool
     * @throws \Exception
     */
    static public function validateLapse($lapse);

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
}