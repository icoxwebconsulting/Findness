<?php

namespace spec\MapRoute\Registration;

use Customer\Customer\Customer;
use MapRoute\MapRoute\MapRoute;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegistrationHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('MapRoute\Registration\RegistrationHandler');
    }

    public function it_should_register_a_map_route()
    {
        $id = uniqid();
        $mapRoute = new MapRoute($id);
        $name = 'route 1';
        $transport = 'car';
        $customer = new Customer($id);
        $mapRoute = $this->registerMapRoute($mapRoute, $name, $transport, $customer);
        $mapRoute->getName()->shouldBe($name);
        $mapRoute->getTransport()->shouldBe($transport);
        $mapRoute->getCustomer()->shouldBe($customer);
    }

    public function it_should_update_a_map_route()
    {
        $id = uniqid();
        $mapRoute = new MapRoute($id);
        $mapRoute->setName('route 1');
        $mapRoute->setTransport('car');
        $customer = new Customer($id);
        $mapRoute->setCustomer($customer);

        $name = 'route 2';
        $transport = 'public';
        $mapRoute = $this->updateMapRoute($mapRoute, $name, $transport);
        $mapRoute->getName()->shouldBe($name);
        $mapRoute->getTransport()->shouldBe($transport);
        $mapRoute->getCustomer()->shouldBe($customer);
    }
}
