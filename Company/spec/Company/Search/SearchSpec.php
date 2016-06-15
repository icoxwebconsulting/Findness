<?php

namespace spec\Company\Search;

use Customer\Customer\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SearchSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Company\Search\Search');
    }

    public function it_construct_with_uniqid()
    {
        $id = uniqid();
        $this->beConstructedWith($id);
        $this->getId()->shouldBe($id);
    }

    public function it_should_set_and_get_name()
    {
        $name = "xyz";
        $this->setName($name);
        $this->getName()->shouldBe($name);
    }

    public function it_should_set_and_get_filters()
    {
        $filters = array(1, 2, 3, [4, 5], [6 => "6"]);
        $this->setFilters($filters);
        $this->getFilters()->shouldBe($filters);
    }

    public function it_should_set_and_get_customer()
    {
        $customer = new Customer();
        $this->setCustomer($customer);
        $this->getCustomer()->shouldBe($customer);
    }
}
