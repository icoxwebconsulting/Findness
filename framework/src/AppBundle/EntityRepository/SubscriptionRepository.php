<?php

namespace AppBundle\EntityRepository;

use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

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
    public function findByCustomer(CustomerInterface $customer, $onlyActive = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->from('AppBundle:Subscription', 's')
            ->innerJoin('s.customer', 'c')
            ->innerJoin('s.transaction', 't', Expr\Join::WITH, 's.transaction = t.id AND s.customer = t.customer')
            ->where('c.id = :customer');
        if($onlyActive)
            $qb->andWhere($qb->expr()->between(':date', 's.startDate', 's.endDate'));

        $qb->orderBy('s.created', 'DESC')
            ->setParameter(':customer', $customer->getId());
        if($onlyActive)
            $qb->setParameter(':date', new \DateTime());

        $results = $qb->getQuery()->getArrayResult();

        return !empty($results) ? $results[0] : null;
    }
}