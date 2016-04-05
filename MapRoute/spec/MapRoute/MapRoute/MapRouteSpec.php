<?php

namespace spec\MapRoute\MapRoute;

use Customer\Customer\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use MapRoute\MapRoutePath\MapRoutePath;
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
        $path1 = new MapRoutePath();
        $this->addPath($path1);
        $path2 = new MapRoutePath();
        $this->addPath($path2);

        $paths = new ArrayCollection();
        $paths->add($path1);
        $paths->add($path2);

        $this->getPaths()->shouldBeLike($paths);
        $this->removePath($path1);
        $paths->removeElement($path1);
        $this->getPaths()->shouldBeLike($paths);

        $path3 = new MapRoutePath();
        $this->addPath($path3);
        $paths = new ArrayCollection();
        $paths->add($path1);
        $paths->add($path2);
        $paths->add($path3);
        $this->setPaths($paths);
        $this->getPaths()->shouldBeLike($paths);
    }
}
