<?php

namespace spec\Finance\Finance;

use Customer\Customer\Customer;
use Finance\Finance\Subscription;
use PhpSpec\ObjectBehavior;

class SubscriptionSpec extends ObjectBehavior
{
    public function it_construct_with_customer_today_6_month()
    {
        $customer = new Customer();
        $lapse = Subscription::SIX_MONTHS;
        $this->beConstructedWith($customer, $lapse);
        $this->getCustomer()->shouldBe($customer);
        $this->shouldHaveType('Finance\Finance\Subscription');
        $this->getLapse()->shouldBe($lapse);
        $this->getStartDate()->format('y-m-d')->shouldBe((new \DateTime())->format('y-m-d'));
        $this->getEndDate()->format('y-m-d')->shouldBe(
            (new \DateTime())->add(new \DateInterval(sprintf("P%dM", $lapse)))->format('y-m-d')
        );
    }

    public function it_construct_with_customer_today_12_month()
    {
        $customer = new Customer();
        $lapse = Subscription::ONE_YEAR;
        $this->beConstructedWith($customer, $lapse);
        $this->getCustomer()->shouldBe($customer);
        $this->shouldHaveType('Finance\Finance\Subscription');
        $this->getLapse()->shouldBe($lapse);
        $this->getStartDate()->format('y-m-d')->shouldBe((new \DateTime())->format('y-m-d'));
        $this->getEndDate()->format('y-m-d')->shouldBe(
            (new \DateTime())->add(new \DateInterval(sprintf("P%dM", $lapse)))->format('y-m-d')
        );
    }

    public function it_construct_with_customer_some_day()
    {
        $customer = new Customer();
        $lapse = Subscription::SIX_MONTHS;
        $startDate = new \DateTime('2010-10-10');
        $this->beConstructedWith($customer, $lapse, $startDate);
        $this->getCustomer()->shouldBe($customer);
        $this->shouldHaveType('Finance\Finance\Subscription');
        $this->getLapse()->shouldBe($lapse);
        $this->getStartDate()->format('y-m-d')->shouldBe($startDate->format('y-m-d'));
        $this->getEndDate()->format('y-m-d')->shouldBe(
            $startDate->add(new \DateInterval(sprintf("P%dM", $lapse)))->format('y-m-d')
        );
    }

    public function it_should_set_customer()
    {
        $customer = new Customer();
        $lapse = Subscription::SIX_MONTHS;
        $this->beConstructedWith($customer, $lapse);
        $this->getCustomer()->shouldBe($customer);
        $customer2 = new Customer();
        $this->setCustomer($customer2);
        $this->getCustomer()->shouldBe($customer2);
    }
}
