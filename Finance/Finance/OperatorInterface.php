<?php

namespace Finance\Finance;

/**
 * Interface Operator
 *
 * @package Finance\Finance
 */
interface OperatorInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Validate transaction
     *
     * @param string $transactionId
     * @param float $balance
     * @param array $apisConf
     * @return bool
     */
    public function validateTransaction($transactionId, $balance, array $apisConf);
}