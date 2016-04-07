<?php

namespace AppBundle\Services;

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
     * Register new customer
     *
     * @param MapRouteInterface $mapRoute
     * @param string $name
     * @param string $transport
     * @param CustomerInterface $customer
     * @return mixed
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
}