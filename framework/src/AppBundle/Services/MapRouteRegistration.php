<?php

namespace AppBundle\Services;

use AppBundle\Entity\MapRoute;
use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityManager;
use MapRoute\MapRoute\MapRouteInterface;
use MapRoute\Registration\RegistrationHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * Validate points are companies that customer can handle
     *
     * @param CustomerInterface $customer
     * @param array $points
     * @return bool
     * @throws HttpException
     */
    private function validatePoints(CustomerInterface $customer, array $points)
    {
        $expr = $this->em->createQueryBuilder()->expr();

        $companies = $this->em->createQueryBuilder()
            ->select('c')
            ->from('AppBundle:Company', 'c')
            ->from('AppBundle:CustomerViewCompany', 'cvc')
            ->where($expr->in('c.id', ':ids'))
            ->andWhere('c.id = cvc.company')
            ->andWhere('cvc.customer = :customer')
            ->setParameter('ids', $points)
            ->setParameter('customer', $customer->getId())
            ->getQuery()
            ->getArrayResult();

        if (count($companies) !== count($points)) {
            throw new HttpException(500, 'Route points are not valid.');
        }

        return true;
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
        $this->validatePoints($customer, $points);

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
     * @param CustomerInterface $customer
     * @param MapRouteInterface $mapRoute
     * @param string $name
     * @param string $transport
     * @param array $points
     * @return MapRoute
     */
    public function update(CustomerInterface $customer,
                           MapRouteInterface $mapRoute,
                           $name,
                           $transport,
                           array $points)
    {
        $this->validatePoints($customer, $points);

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

        $companies = $this->em->createQueryBuilder()
            ->select('c')
            ->from('AppBundle:Company', 'c')
            ->where($expr->in('c.id', ':ids'))
            ->setParameter('ids', $mapRoute->getPath())
            ->getQuery()
            ->getResult();

        $points = [];
        foreach ($companies as $company) {
            $points[$company->getId()] = [
                "id" => $company->getId(),
                "externalId" => $company->getExternalId(),
                "socialReason" => $company->getSocialReason(),
                "socialObject" => $company->getSocialObject(),
                "latitude" => $company->getLatitude(),
                "longitude" => $company->getLongitude(),
                "cif" => $company->getCif(),
                "address" => $company->getAddress(),
                "phoneNumber" => $company->getPhoneNumber()
            ];
        }

        return [
            "id" => $mapRoute->getId(),
            "name" => $mapRoute->getName(),
            "transport" => $mapRoute->getTransport(),
            "points" => $points
        ];
    }
}