<?php

namespace AppBundle\ResponseObjects;

use MapRoute\MapRoute\MapRouteInterface;

/**
 * Class MapRoute
 *
 * @package AppBundle\ResponseObjects
 */
class MapRoute
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $transport;

    /**
     * MapRoute constructor.
     *
     * @param MapRouteInterface $mapRoute
     */
    public function __construct(MapRouteInterface $mapRoute)
    {
        $this->id = $mapRoute->getId();
        $this->name = $mapRoute->getName();
        $this->transport = $mapRoute->getTransport();
    }
}