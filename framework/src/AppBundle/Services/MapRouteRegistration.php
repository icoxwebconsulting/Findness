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

        $ownedCompanyEXPR = $expr->andX('cvc.customer = :customer');
        $sharedCompanyEXPR = $expr->andX(
            'ssl.customer = :customer',
            'ssl.staticList = sl.id',
            'slc.id = cvc.company'
        );

        $allowedEXPR = $expr->orX($ownedCompanyEXPR, $sharedCompanyEXPR);

        $companies = $this->em->createQueryBuilder()
            ->select('cvc')
            ->distinct()
            ->from('AppBundle:CustomerViewCompany', 'cvc')
            ->from('AppBundle:SharedStaticList', 'ssl')
            ->from('AppBundle:StaticList', 'sl')
            ->innerJoin('sl.companies', 'slc')
            ->where($expr->in('cvc.company', ':ids'))
            ->andWhere($allowedEXPR)
            ->setParameter('ids', $points)
            ->setParameter('customer', $customer->getId())
            ->getQuery()
            ->getArrayResult();

        $ids = array_reduce(
            $companies,
            function ($carry, $current) {
                $carry[] = $current['company'];

                return $carry;
            },
            []
        );

        if (count($companies) !== count($points) && $points != $ids) {
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
    public function register(
        MapRouteInterface $mapRoute,
        $name,
        $transport,
        CustomerInterface $customer,
        array $points
    ) {
        $this->validatePoints($customer, $points);

        $handler = new RegistrationHandler();
        $mapRoute = $handler->registerMapRoute(
            $mapRoute,
            $name,
            $transport,
            $customer,
            $points
        );
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
    public function update(
        CustomerInterface $customer,
        MapRouteInterface $mapRoute,
        $name,
        $transport,
        array $points
    ) {
        $this->validatePoints($customer, $points);

        $handler = new RegistrationHandler();
        $mapRoute = $handler->updateMapRoute(
            $mapRoute,
            $name,
            $transport,
            $points
        );
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
        $points = [];
        foreach ($mapRoute->getPath() as $companyId) {
            $company = $this->em->createQueryBuilder()
                ->select('c')
                ->from('AppBundle:Company', 'c')
                ->where('c.id = :id')
                ->setParameter('id', $companyId)
                ->getQuery()
                ->getSingleResult();

            $points[] = [
                "id" => $company->getId(),
                "externalId" => $company->getExternalId(),
                "socialReason" => $company->getSocialReason(),
                "socialObject" => $company->getSocialObject(),
                "latitude" => $company->getLatitude(),
                "longitude" => $company->getLongitude(),
                "cif" => $company->getCif(),
                "address" => $company->getAddress(),
                "phoneNumber" => $company->getPhoneNumber(),
            ];
        }

        return [
            "id" => $mapRoute->getId(),
            "name" => $mapRoute->getName(),
            "transport" => $mapRoute->getTransport(),
            "points" => $points,
        ];
    }
}