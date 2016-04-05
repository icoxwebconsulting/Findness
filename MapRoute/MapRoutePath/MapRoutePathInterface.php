<?php

namespace MapRoute\MapRoutePath;

use MapRoute\MapRoute\MapRouteInterface;

/**
 * Interface MapRoutePath
 *
 * @package MapRoutePath\MapRoutePath
 */
interface MapRoutePathInterface
{
    /**
     * Get MapRoutePath id
     *
     * @return string
     */
    public function getId();

    /**
     * Set MapRoutePath Start Point
     *
     * @param string $latitude
     * @param string $longitude
     */
    public function setStartPoint($latitude, $longitude);

    /**
     * Get MapRoutePath Start Point
     *
     * @return array
     */
    public function getStartPoint();

    /**
     * Set MapRoutePath End Point
     *
     * @param string $latitude
     * @param string $longitude
     */
    public function setEndPoint($latitude, $longitude);

    /**
     * Get MapRoutePath End Point
     *
     * @return array
     */
    public function getEndPoint();

    /**
     * Set MapRoutePath MapRoute
     *
     * @param MapRouteInterface $mapRoute
     */
    public function setMapRoute(MapRouteInterface $mapRoute);

    /**
     * Get MapRoutePath MapRoute
     *
     * @return MapRouteInterface
     */
    public function getMapRoute();
}