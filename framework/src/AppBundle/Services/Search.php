<?php

namespace AppBundle\Services;

use AppBundle\Entity\Search as SearchEntity;
use Customer\Customer\CustomerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Search
 *
 * @package AppBundle\Services
 */
class Search
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Company constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param CustomerInterface $customer
     * @param SearchEntity $search
     * @param string $name
     * @return bool
     */
    public function update(CustomerInterface $customer, SearchEntity $search, $name)
    {
        if ($customer->getId() !== $search->getCustomer()->getId()) {
            throw new HttpException(500, 'Customer is not owner of search.');
        }

        try {
            $search->setName($name);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new HttpException(500, 'Search name not unique.');
        }

        return true;
    }

    /**
     * Delete search
     *
     * @param CustomerInterface $customer
     * @param SearchEntity $search
     * @return bool
     */
    public function delete(CustomerInterface $customer, SearchEntity $search)
    {
        if ($customer->getId() !== $search->getCustomer()->getId()) {
            throw new HttpException(500, 'Customer is not owner of search.');
        }

        $this->em->remove($search);
        $this->em->flush();

        return true;
    }
}