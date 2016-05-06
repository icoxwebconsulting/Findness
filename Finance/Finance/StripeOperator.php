<?php

namespace Finance\Finance;

use Stripe\Charge;
use Stripe\Stripe;

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
    public function validateTransaction($transactionId, $balance, array $apisConf)
    {
        try {
            Stripe::setApiKey($apisConf["secret"]);
            $charge = Charge::retrieve($transactionId);
            $response = $charge->getLastResponse()->json;
            if ($response["id"] !== $transactionId || (float)($response["amount"] / 100) !== (float)$balance) {
                throw new \Exception();
            }
            return true;
        } catch (\Exception $exception) {
            throw new \Exception("Transaction do not exist");
        }
    }
}