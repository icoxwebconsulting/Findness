<?php

namespace AppBundle\Services;

use Customer\Customer\CustomerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use StaticList\Registration\RegistrationHandler;
use StaticList\StaticList\StaticListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Acl\Exception\Exception;

/**
 * Class StaticList
 *
 * @package AppBundle\Services
 */
class StaticList
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
     * @param string $name
     * @param array $companies
     * @return StaticListInterface;
     */
    public function register(CustomerInterface $customer, $name, array $companies)
    {
        $companyEntities = [];
        foreach ($companies as $company) {
            try {
                $companyEntities[] = $this->em->createQueryBuilder()
                    ->select('c')
                    ->from('AppBundle:Company', 'c')
                    ->from('AppBundle:CustomerViewCompany', 'cvc')
                    ->where('c.id = cvc.company')
                    ->andWhere('cvc.customer = :customer')
                    ->andWhere('cvc.company = :company')
                    ->setParameter('customer', $customer->getId())
                    ->setParameter('company', $company)
                    ->getQuery()
                    ->getSingleResult();
            } catch (NoResultException $exception) {
                throw new HttpException(500, 'Company not found or does not belong to the user.');
            }
        }

        $register = new RegistrationHandler();
        $staticList = $register->register($customer, $name, $companyEntities);
        $staticList = \AppBundle\Entity\StaticList::fromBusinessEntity($staticList);

        try {
            $this->em->persist($staticList);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new HttpException(500, 'Not unique list name.');
        }
        return $staticList;
    }
}