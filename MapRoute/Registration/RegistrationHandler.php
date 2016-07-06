<?php

namespace MapRoute\Registration;

use Customer\Customer\CustomerInterface;
use MapRoute\MapRoute\MapRouteInterface;

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
     * @param array $points
     * @return MapRouteInterface
     */
    public function registerMapRoute(MapRouteInterface $mapRoute,
                                     $name,
                                     $transport,
                                     CustomerInterface $customer,
                                     array $points)
    {
        $mapRoute->setName($name);
        $mapRoute->setTransport($transport);
        $mapRoute->setCustomer($customer);
        $mapRoute->setPath($points);
        return $mapRoute;
    }

    /**
     * Handle map route update logic
     *
     * @param MapRouteInterface $mapRoute
     * @param string $name
     * @param string $transport
     * @param array $points
     * @return MapRouteInterface
     */
    public function updateMapRoute(MapRouteInterface $mapRoute,
                                   $name,
                                   $transport,
                                   array $points)
    {
        $mapRoute->setName($name);
        $mapRoute->setTransport($transport);
        $mapRoute->setPath($points);
        return $mapRoute;
    }
}