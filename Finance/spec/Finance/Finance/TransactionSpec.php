<?php

namespace spec\Finance\Finance;

use Customer\Customer\Customer;
use Finance\Finance\FindnessOperator;
use Finance\Finance\PaypalOperator;
use Finance\Finance\StripeOperator;
use PhpSpec\ObjectBehavior;

class TransactionSpec extends ObjectBehavior
{
    public function it_construct_with_customer()
    {
        $customer = new Customer();
        $this->beConstructedWith($customer);
        $this->getCustomer()->shouldBe($customer);
        $this->shouldHaveType('Finance\Finance\Transaction');
    }

    public function it_construct_with_customer_and_id()
    {
        $customer = new Customer();
        $id = uniqid();
        $this->beConstructedWith($customer, $id);
        $this->getCustomer()->shouldBe($customer);
        $this->getId()->shouldBe($id);
        $this->shouldHaveType('Finance\Finance\Transaction');
    }

    public function it_should_set_customer()
    {
        $customer = new Customer();
        $this->beConstructedWith($customer);
        $this->getCustomer()->shouldBe($customer);
        $customer2 = new Customer();
        $this->setCustomer($customer2);
        $this->getCustomer()->shouldBe($customer2);
    }

    public function it_should_set_and_get_balance()
    {
        $customer = new Customer();
        $this->beConstructedWith($customer);
        $balance = 12.2;
        $this->setBalance($balance);
        $this->getBalance()->shouldBe($balance);

        $balance = -12.2;
        $this->setBalance($balance);
        $this->getBalance()->shouldBe($balance);
    }

    public function it_should_set_and_get_operator()
    {
        $customer = new Customer();
        $this->beConstructedWith($customer);
        $findnessOperator = new FindnessOperator();
        $this->setOperator($findnessOperator);
        $this->getOperator()->shouldBe($findnessOperator);

        $paypalOperator = new PaypalOperator();
        $this->setOperator($paypalOperator);
        $this->getOperator()->shouldBe($paypalOperator);

        $stripeOperator = new StripeOperator();
        $this->setOperator($stripeOperator);
        $this->getOperator()->shouldBe($stripeOperator);
    }

    public function it_should_set_and_get_reference()
    {
        $customer = new Customer();
        $this->beConstructedWith($customer);
        $reference = uniqid();
        $this->setReference($reference);
        $this->getReference()->shouldBe($reference);

        $reference = sprintf("%s@%s", uniqid(), uniqid());
        $this->setReference($reference);
        $this->getReference()->shouldBe($reference);
    }
}
