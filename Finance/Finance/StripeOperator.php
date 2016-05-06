<?php

namespace Finance\Finance;

/**
 * Class StripeOperator
 *
 * @package Finance\Finance
 */
class StripeOperator implements OperatorInterface
{

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return 3;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "stripe";
    }

    /**
     * @inheritdoc
     */
    public function validateTransaction($transactionId, array $apisConf)
    {
        return true;
    }
}