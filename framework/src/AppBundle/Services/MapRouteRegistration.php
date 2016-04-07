<?php

namespace AppBundle\Services;

use AppBundle\Entity\MapRoute;
use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityManager;
use MapRoute\MapRoute\MapRouteInterface;
use MapRoute\Registration\RegistrationHandler;

/**
 * Class MapRouteRegistration
 *
 * @package AppBundle\Services
 */
class MapRouteRegistration
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * MapRouteRegistration constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Register new Map Route
     *
     * @param MapRouteInterface $mapRoute
     * @param string $name
     * @param string $transport
     * @param CustomerInterface $customer
     * @return MapRoute
     */
    public function register(MapRouteInterface $mapRoute,
                             $name,
                             $transport,
                             CustomerInterface $customer)
    {
        $handler = new RegistrationHandler();
        $mapRoute = $handler->registerMapRoute($mapRoute,
            $name,
            $transport,
            $customer);
        $this->em->persist($mapRoute);
        $this->em->flush();
        return $mapRoute;
    }

    /**
     * Update a Map Route
     *
     * @param MapRouteInterface $mapRoute
     * @param string $name
     * @param string $transport
     * @return MapRoute
     */
    public function update(MapRouteInterface $mapRoute,
                           $name,
                           $transport)
    {
        $handler = new RegistrationHandler();
        $mapRoute = $handler->updateMapRoute($mapRoute,
            $name,
            $transport);
        $this->em->flush();
        return $mapRoute;
    }
}