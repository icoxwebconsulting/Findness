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
}