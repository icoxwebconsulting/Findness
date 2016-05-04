<?php

namespace spec\Finance\Finance;

use Customer\Customer\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BalanceSpec extends ObjectBehavior
{
    public function it_construct_with_customer()
    {
        $customer = new Customer();
        $this->beConstructedWith($customer);
        $this->getCustomer()->shouldBe($customer);
        $this->shouldHaveType('Finance\Finance\Balance');
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

    public function it_should_set_get_balance()
    {
        $customer = new Customer();
        $this->beConstructedWith($customer);
        $this->getCustomer()->shouldBe($customer);
        $balance = 12.2;
        $this->setBalance($balance);
        $this->getBalance()->shouldBe($balance);
    }
}
