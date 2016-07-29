<?php

namespace AppBundle\EntityRepository;

use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class TransactionRepository
 *
 * @package AppBundle\EntityRepository
 */
class TransactionRepository extends EntityRepository
{
    /**
     * @param CustomerInterface $customer
     * @return array
     */
    public function findByCustomer(CustomerInterface $customer)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('t')
            ->from('AppBundle:Transaction', 't')
            ->innerJoin('t.customer', 'c')
            ->where('c.id = :customer')
            ->orderBy('t.created', 'DESC')
            ->setParameter(':customer', $customer->getId());

        return $qb->getQuery()->getResult();
    }
}