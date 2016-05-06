<?php

namespace Finance\Finance;

use PayPal\Api\Sale;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

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
    public function validateTransaction($transactionId, $balance, array $apisConf)
    {
        try {
            $apiContext = new ApiContext(new OAuthTokenCredential($apisConf["id"], $apisConf["secret"]));
            $sale = Sale::get($transactionId, $apiContext);

            if ($sale->id !== $transactionId || (float)$sale->amount->total !== (float)$balance) {
                throw new \Exception();
            }
            return true;
        } catch (\Exception $exception) {
            throw new \Exception("Transaction do not exist");
        }
    }
}