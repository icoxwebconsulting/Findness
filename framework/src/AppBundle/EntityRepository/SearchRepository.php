<?php

namespace AppBundle\EntityRepository;

use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class SearchRepository
 *
 * @package AppBundle\EntityRepository
 */
class SearchRepository extends EntityRepository
{
    /**
     * Get ordered by create date desc
     *
     * @param CustomerInterface $customer
     * @return mixed
     */
    public function getOrderedByCreatedDesc(CustomerInterface $customer)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->from('AppBundle:Search', 's')
            ->where('s.customer', ':customer')
            ->orderBy('s.created DESC')
            ->setParameter(':customer', $customer->getId());

        return $qb->getQuery()->getResult();
    }
}