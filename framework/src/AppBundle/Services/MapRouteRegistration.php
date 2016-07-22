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
     * @param array $points
     * @return MapRoute
     */
    public function register(MapRouteInterface $mapRoute,
                             $name,
                             $transport,
                             CustomerInterface $customer,
                             array $points)
    {
        $handler = new RegistrationHandler();
        $mapRoute = $handler->registerMapRoute($mapRoute,
            $name,
            $transport,
            $customer,
            $points);
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
     * @param array $points
     * @return MapRoute
     */
    public function update(MapRouteInterface $mapRoute,
                           $name,
                           $transport,
                           array $points)
    {
        $handler = new RegistrationHandler();
        $mapRoute = $handler->updateMapRoute($mapRoute,
            $name,
            $transport,
            $points);
        $this->em->flush();
        return $mapRoute;
    }

    /**
     * Get Map Route
     *
     * @param MapRouteInterface $mapRoute
     * @return array
     */
    public function get(MapRouteInterface $mapRoute)
    {
        $expr = $this->em->createQueryBuilder()->expr();

        $points = $this->em->createQueryBuilder()
            ->select('c')
            ->from('AppBundle:Company', 'c')
            ->where($expr->in('c.id', ':ids'))
            ->setParameter('ids', $mapRoute->getPath())
            ->getQuery()
            ->getResult();

        return [
            "id" => $mapRoute->getId(),
            "name" => $mapRoute->getName(),
            "transport" => $mapRoute->getTransport(),
            "points" => $points
        ];
    }
}