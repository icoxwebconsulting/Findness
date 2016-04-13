<?php

namespace AppBundle\ResponseObjects;

use MapRoute\MapRoutePath\MapRoutePathInterface;

/**
 * Class MapRoutePath
 *
 * @package AppBundle\ResponseObjects
 */
class MapRoutePath
{
    /**
     * @var string
     */
    public $id;

    /**
     * MapRoutePath constructor.
     *
     * @param MapRoutePathInterface $mapRoutePath
     */
    public function __construct(MapRoutePathInterface $mapRoutePath)
    {
        $this->id = $mapRoutePath->getId();
    }
}