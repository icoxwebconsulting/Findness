<?php

namespace AppBundle\EntityRepository;

use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class SubscriptionRepository
 *
 * @package AppBundle\EntityRepository
 */
class SubscriptionRepository extends EntityRepository
{
    /**
     * @param CustomerInterface $customer
     * @return array
     */
    public function findByCustomer(CustomerInterface $customer)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->from('AppBundle:Subscription', 's')
            ->innerJoin('s.customer', 'c')
            ->where('s.id = :customer')
            ->orderBy('s.created', 'DESC')
            ->setParameter(':customer', $customer->getId());

        return $qb->getQuery()->getResult();
    }
}