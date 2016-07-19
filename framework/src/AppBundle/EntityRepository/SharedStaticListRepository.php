<?php

namespace AppBundle\EntityRepository;

use AppBundle\Entity\StaticList;
use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class SharedStaticListRepository
 *
 * @package AppBundle\EntityRepository
 */
class SharedStaticListRepository extends EntityRepository
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
            ->select('ssl')
            ->from('AppBundle:SharedStaticList', 'ssl')
            ->join('ssl.staticList', 'sl')
            ->andWhere($expr->orX('sl.customer = :customer', 'ssl.customer = :shared'))
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
            ->select('ssl')
            ->from('AppBundle:SharedStaticList', 'ssl')
            ->join('ssl.staticList', 'sl')
            ->where('sl.id = :staticList')
            ->andWhere($expr->orX('sl.customer = :customer', 'ssl.customer = :shared'))
            ->setParameter('staticList', $staticListId)
            ->setParameter('customer', $customer->getId())
            ->setParameter('shared', $customer->getId())
            ->getQuery()
            ->getSingleResult();
    }
}