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
}