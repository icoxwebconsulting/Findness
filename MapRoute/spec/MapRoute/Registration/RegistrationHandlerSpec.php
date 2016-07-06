<?php

namespace spec\MapRoute\Registration;

use Customer\Customer\Customer;
use MapRoute\MapRoute\MapRoute;
use MapRoute\MapRoutePath\MapRoutePath;
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
        $mapRoutePath = array(1, 2, 3);
        $mapRoute = $this->registerMapRoute($mapRoute, $name, $transport, $customer, $mapRoutePath);
        $mapRoute->getName()->shouldBe($name);
        $mapRoute->getTransport()->shouldBe($transport);
        $mapRoute->getCustomer()->shouldBe($customer);
        $mapRoute->getPath()->shouldBe($mapRoutePath);
    }

    public function it_should_update_a_map_route()
    {
        $id = uniqid();
        $mapRoute = new MapRoute($id);
        $mapRoute->setName('route 1');
        $mapRoute->setTransport('car');
        $customer = new Customer($id);
        $mapRoute->setCustomer($customer);
        $mapRoutePath = array(1, 2, 3);
        $mapRoute->setPath($mapRoutePath);

        $name = 'route 2';
        $transport = 'public';
        $mapRoutePath = array(4, 5, 6);
        $mapRoute = $this->updateMapRoute($mapRoute, $name, $transport, $mapRoutePath);
        $mapRoute->getName()->shouldBe($name);
        $mapRoute->getTransport()->shouldBe($transport);
        $mapRoute->getCustomer()->shouldBe($customer);
        $mapRoute->getPath()->shouldBe($mapRoutePath);
    }
}
