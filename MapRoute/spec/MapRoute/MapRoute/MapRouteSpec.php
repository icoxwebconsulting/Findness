<?php

namespace spec\MapRoute\MapRoute;

use Customer\Customer\Customer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MapRouteSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('MapRoute\MapRoute\MapRoute');
    }

    public function it_construct_with_uniqid()
    {
        $id = uniqid();
        $this->beConstructedWith($id);
        $this->getId()->shouldBe($id);
    }

    public function it_should_set_and_get_name()
    {
        $name = "route 1";
        $this->setName($name);
        $this->getName()->shouldBe($name);
    }

    public function it_should_set_and_get_transport()
    {
        $transport = "Car";
        $this->setTransport($transport);
        $this->getTransport()->shouldBe($transport);
    }

    public function it_should_set_and_get_customer()
    {
        $customer = new Customer();
        $this->setCustomer($customer);
        $this->getCustomer()->shouldBe($customer);
    }

    public function it_should_set_and_get_map_route_path()
    {
        $mapRoutePath = array(1, 2, 3);
        $this->setPath($mapRoutePath);
        $this->getPath()->shouldBe($mapRoutePath);
    }
}
