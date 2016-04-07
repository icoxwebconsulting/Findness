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
     * Handle a new customer registration logic
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
}