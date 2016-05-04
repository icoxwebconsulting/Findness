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
}