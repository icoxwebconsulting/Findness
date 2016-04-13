<?php

namespace MapRoute\Registration;

use Customer\Customer\CustomerInterface;
use MapRoute\MapRoute\MapRouteInterface;
use MapRoute\MapRoutePath\MapRoutePathInterface;

/**
 * Class RegistrationHandler
 *
 * @package MapRoute\Registration
 */
class RegistrationHandler
{
    /**
     * Handle map route registration logic
     *
     * @param MapRouteInterface $mapRoute
     * @param string $name
     * @param string $transport
     * @param CustomerInterface $customer
     * @return MapRouteInterface
     */
    public function registerMapRoute(MapRouteInterface $mapRoute,
                                     $name,
                                     $transport,
                                     CustomerInterface $customer)
    {
        $mapRoute->setName($name);
        $mapRoute->setTransport($transport);
        $mapRoute->setCustomer($customer);
        return $mapRoute;
    }

    /**
     * Handle map route update logic
     *
     * @param MapRouteInterface $mapRoute
     * @param string $name
     * @param string $transport
     * @return MapRouteInterface
     */
    public function updateMapRoute(MapRouteInterface $mapRoute,
                                   $name,
                                   $transport)
    {
        $mapRoute->setName($name);
        $mapRoute->setTransport($transport);
        return $mapRoute;
    }

    /**
     * Handle map route path registration logic
     *
     * @param MapRouteInterface $mapRoute
     * @param MapRoutePathInterface $mapRoutePath
     * @param array $startPoint
     * @param array $endPoint
     * @return MapRoutePathInterface
     */
    public function registerMapRoutePath(MapRouteInterface $mapRoute,
                                         MapRoutePathInterface $mapRoutePath,
                                         array $startPoint,
                                         array $endPoint)
    {
        $mapRoutePath->setMapRoute($mapRoute);
        $mapRoutePath->setStartPoint($startPoint["latitude"], $startPoint["longitude"]);
        $mapRoutePath->setEndPoint($endPoint["latitude"], $endPoint["longitude"]);
        return $mapRoutePath;
    }

    /**
     * Handle map route path update logic
     *
     * @param MapRoutePathInterface $mapRoutePath
     * @param array $startPoint
     * @param array $endPoint
     * @return MapRoutePathInterface
     */
    public function updateMapRoutePath(MapRoutePathInterface $mapRoutePath,
                                       array $startPoint,
                                       array $endPoint)
    {
        $mapRoutePath->setStartPoint($startPoint["latitude"], $startPoint["longitude"]);
        $mapRoutePath->setEndPoint($endPoint["latitude"], $endPoint["longitude"]);
        return $mapRoutePath;
    }
}