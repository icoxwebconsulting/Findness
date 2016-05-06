<?php

namespace Finance\Finance;

/**
 * Class PaypalOperator
 *
 * @package Finance\Finance
 */
class PaypalOperator implements OperatorInterface
{

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return 2;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "paypal";
    }

    /**
     * @inheritdoc
     */
    public function validateTransaction($transactionId, array $apisConf)
    {
        return true;
    }
}