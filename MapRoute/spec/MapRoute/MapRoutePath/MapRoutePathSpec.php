<?php

namespace spec\MapRoute\MapRoutePath;

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
        $latitude = "1111111111111111111";
        $longitude = "2222222222222222222";
        $this->setStartPoint($latitude, $longitude);
        $this->getStartPoint()->shouldBe(["latitude" => $latitude, "longitude" => $longitude]);
    }

    public function it_should_set_and_get_end_point()
    {
        $latitude = "1111111111111111111";
        $longitude = "2222222222222222222";
        $this->setEndPoint($latitude, $longitude);
        $this->getEndPoint()->shouldBe(["latitude" => $latitude, "longitude" => $longitude]);
    }

    public function it_should_set_and_get_map_route()
    {
        $mapRoute = new MapRoute();
        $this->setMaproute($mapRoute);
        $this->getMapRoute()->shouldBe($mapRoute);
    }
}
