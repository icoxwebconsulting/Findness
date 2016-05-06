<?php

namespace Finance\Finance;

/**
 * Class FindnessOperator
 *
 * @package Finance\Finance
 */
class FindnessOperator implements OperatorInterface
{

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "findness";
    }

    /**
     * @inheritdoc
     */
    public function validateTransaction($transactionId, array $apisConf)
    {
        return true;
    }
}