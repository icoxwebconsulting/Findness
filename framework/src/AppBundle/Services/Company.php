<?php

namespace AppBundle\Services;

use AppBundle\Entity\StyledCompany;
use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Acl\Exception\Exception;

/**
 * Class Company
 *
 * @package AppBundle\Services
 */
class Company
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
     * @param $company
     * @param $style
     * @return bool;
     */
    public function updateStyle(CustomerInterface $customer, $company, $style)
    {
        $styledCompany = $this->em->getRepository('AppBundle:StyledCompany')
            ->findOneBy(array(
                'company' => $company,
                'customer' => $customer->getId()
            ));

        if (!$styledCompany) {
            $company = $this->em->getRepository('AppBundle:Company')->find($company);

            if (!$company) {
                throw new HttpException(500, 'Company not found.');
            }

            $styledCompany = new StyledCompany($company, $customer);
            $this->em->persist($styledCompany);
        }

        $styledCompany->setStyle($style);

        $this->em->flush();

        return true;
    }
}