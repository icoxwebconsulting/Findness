<?php

namespace MapRoute\MapRoutePath;

use MapRoute\MapRoute\MapRouteInterface;

/**
 * Class MapRoutePath
 *
 * @package MapRoutePath\MapRoutePath
 */
class MapRoutePath implements MapRoutePathInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $startPoint;

    /**
     * @var array
     */
    protected $endPoint;

    /**
     * @var MapRouteInterface
     */
    protected $mapRoute;

    /**
     * MapRoutePath constructor.
     * @param string|null $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
        if (!$this->id) {
            $this->id = uniqid();
        }
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function setStartPoint($latitude, $longitude)
    {
        $this->startPoint = [
            "latitude" => $latitude,
            "longitude" => $longitude
        ];
    }

    /**
     * @inheritdoc
     */
    public function getStartPoint()
    {
        return $this->startPoint;
    }

    /**
     * @inheritdoc
     */
    public function setEndPoint($latitude, $longitude)
    {
        $this->endPoint = [
            "latitude" => $latitude,
            "longitude" => $longitude
        ];
    }

    /**
     * @inheritdoc
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * @inheritdoc
     */
    public function setMapRoute(MapRouteInterface $mapRoute)
    {
        $this->mapRoute = $mapRoute;
    }

    /**
     * @inheritdoc
     */
    public function getMapRoute()
    {
        return $this->mapRoute;
    }
}