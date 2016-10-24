<?php

namespace spec\Finance\Registration;

use Customer\Customer\Customer;
use Finance\Finance\FindnessOperator;
use Finance\Finance\Transaction;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegistrationHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Finance\Registration\RegistrationHandler');
    }

    public function it_should_register_a_transaction()
    {
        $apiConfig = [
            "stripe" => [
                "key" => "",
                "secret" => ""
            ],
            "paypal" => [
                "id" => "",
                "secret" => ""
            ],
            "findness" => [

            ]
        ];

        $customer = new Customer();
        $transaction = new Transaction($customer);
        $balance = 12.1;
        $operator = 1;
        $transaction = $this->register($transaction, $balance, $operator, null, null, $apiConfig);
        $transaction->getBalance()->shouldBe($balance);
        $transaction->getOperator()->getId()->shouldBe($operator);

        $customer = new Customer();
        $transaction = new Transaction($customer);
        $balance = 12.2;
        $operator = 2;
        $transactionId = uniqid();
        $cardId = uniqid();
        $transaction = $this->register($transaction, $balance, $operator, $transactionId, $cardId, $apiConfig);
        $transaction->getBalance()->shouldBe($balance);
        $transaction->getOperator()->getId()->shouldBe($operator);
        $transaction->getTransactionId()->shouldBe($transactionId);
        $transaction->getCardId()->shouldBe($cardId);

        $customer = new Customer();
        $transaction = new Transaction($customer);
        $balance = 12.3;
        $operator = 3;
        $transactionId = uniqid();
        $cardId = uniqid();
        $transaction = $this->register($transaction, $balance, $operator, $transactionId, $cardId, $apiConfig);
        $transaction->getBalance()->shouldBe($balance);
        $transaction->getOperator()->getId()->shouldBe($operator);
        $transaction->getTransactionId()->shouldBe($transactionId);
        $transaction->getCardId()->shouldBe($cardId);
    }

    public function it_should_get_transaction_for_search()
    {
        $apiConfig = [
            "stripe" => [
                "key" => "",
                "secret" => ""
            ],
            "paypal" => [
                "id" => "",
                "secret" => ""
            ],
            "findness" => [

            ]
        ];

        $customer = new Customer();
        $balance = 10;
        $transaction = $this->charge($customer, $balance, $apiConfig);
        $transaction->getBalance()->shouldBe($balance);
        $transaction->getOperator()->getId()->shouldBe((new FindnessOperator())->getId());
    }
}
