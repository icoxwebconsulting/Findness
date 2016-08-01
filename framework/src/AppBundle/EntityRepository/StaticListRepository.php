<?php

namespace AppBundle\EntityRepository;

use AppBundle\Entity\StaticList;
use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class StaticListRepository
 *
 * @package AppBundle\EntityRepository
 */
class StaticListRepository extends EntityRepository
{
    /**
     * @param CustomerInterface $customer
     * @return StaticList
     */
    public function allByCustomer(CustomerInterface $customer)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        return $qb
            ->select('sl')
            ->from('AppBundle:StaticList', 'sl')
            ->leftJoin('sl.shareds', 'ssl')
            ->where($expr->orX('sl.customer = :customer', 'ssl.customer = :shared'))
            ->setParameter('customer', $customer->getId())
            ->setParameter('shared', $customer->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param CustomerInterface $customer
     * @param $staticListId
     * @return StaticList
     */
    public function byCustomer(CustomerInterface $customer, $staticListId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        return $qb
            ->select('sl')
            ->from('AppBundle:StaticList', 'sl')
            ->leftJoin('sl.shareds', 'ssl')
            ->where('sl.id = :staticList')
            ->andWhere($expr->orX('sl.customer = :customer', 'ssl.customer = :shared'))
            ->setParameter('staticList', $staticListId)
            ->setParameter('customer', $customer->getId())
            ->setParameter('shared', $customer->getId())
            ->getQuery()
            ->getSingleResult();
    }
}