<?php

namespace spec\StaticList\StaticList;

use Customer\Customer\Customer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use StaticList\StaticList\StaticList;

class SharedStaticListSpec extends ObjectBehavior
{
    public function it_construct()
    {
        $owner = new Customer();
        $staticList = new StaticList($owner, uniqid());
        $shared = new Customer();
        $this->beConstructedWith($shared, $staticList);
        $this->getCustomer()->shouldBe($shared);
        $this->getStaticList()->shouldBe($staticList);
        $this->shouldHaveType('StaticList\StaticList\SharedStaticList');
    }

    public function it_should_throw_exception_on_construct_since_owner_is_shared()
    {
        $owner = new Customer();
        $staticList = new StaticList($owner, uniqid());
        $this->shouldThrow('\Exception')->during('__construct', array($owner, $staticList));
    }

    public function it_should_set_and_get_customer()
    {
        $owner = new Customer();
        $staticList = new StaticList($owner, uniqid());
        $shared = new Customer();
        $this->beConstructedWith($shared, $staticList);

        $customer = new Customer();
        $this->setCustomer($customer);
        $this->getCustomer()->shouldBe($customer);
    }

    public function it_should_throw_exception_on_set_customer_since_owner_is_shared()
    {
        $owner = new Customer();
        $staticList = new StaticList($owner, uniqid());
        $shared = new Customer();
        $this->beConstructedWith($shared, $staticList);

        $this->shouldThrow('\Exception')->during('setCustomer', array($owner));
    }

    public function it_should_set_and_get_static_list()
    {
        $owner = new Customer();
        $staticList = new StaticList($owner, uniqid());
        $shared = new Customer();
        $this->beConstructedWith($shared, $staticList);

        $owner = new Customer();
        $staticList = new StaticList($owner, uniqid());
        $this->setStaticList($staticList);
        $this->getStaticList()->shouldBe($staticList);
    }

    public function it_should_throw_exception_on_set_static_list_since_owner_is_shared()
    {

        $owner = new Customer();
        $staticList = new StaticList($owner, uniqid());
        $shared = new Customer();
        $this->beConstructedWith($shared, $staticList);

        $staticList = new StaticList($shared, uniqid());
        $this->shouldThrow('\Exception')->during('setStaticList', array($staticList));
    }
}
