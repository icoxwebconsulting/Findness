<?php

namespace spec\MapRoute\MapRoutePath;

use Company\Company\Company;
use Doctrine\Common\Collections\ArrayCollection;
use MapRoute\MapRoute\MapRoute;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MapRoutePathSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('MapRoute\MapRoutePath\MapRoutePath');
    }

    public function it_construct_with_uniqid()
    {
        $id = uniqid();
        $this->beConstructedWith($id);
        $this->getId()->shouldBe($id);
    }

    public function it_should_set_and_get_start_point()
    {
        $company = new Company();
        $this->setStartPoint($company);
        $this->getStartPoint()->shouldBe($company);
    }

    public function it_should_set_and_get_end_point()
    {
        $company = new Company();
        $this->setEndPoint($company);
        $this->getEndPoint()->shouldBe($company);
    }

    public function it_should_set_and_get_map_route()
    {
        $mapRoute = new MapRoute();
        $this->setMaproute($mapRoute);
        $this->getMapRoute()->shouldBe($mapRoute);
    }
}
