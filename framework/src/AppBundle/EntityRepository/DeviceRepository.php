<?php

namespace AppBundle\EntityRepository;

use Doctrine\ORM\EntityRepository;

/**
 * Class DeviceRepository
 *
 * @package AppBundle\EntityRepository
 */
class DeviceRepository extends EntityRepository
{
    /**
     * Get by ids
     *
     * @param string $customer
     * @return mixed
     */
    public function getByCustomer($customer)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('d')
            ->from('AppBundle:Device', 'd')
            ->innerJoin('d.customer', 'c')
            ->where('c.username = :customer')
            ->setParameter(':customer', $customer);

        return $qb->getQuery()->getResult();
    }
}