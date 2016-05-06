<?php

namespace AppBundle\Services;

use AppBundle\Entity\Company;
use AppBundle\Entity\CustomerViewCompany;
use AppBundle\Entity\Transaction;
use BeSimple\SoapClient\SoapClient;
use Company\Qualitas\SOAPApi;
use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

/**
 * Class QualitasSOAPApi
 *
 * @package AppBundle\Services
 */
class QualitasSOAPApi extends SOAPApi
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * QualitasSOAPApi constructor.
     *
     * @param string $username
     * @param string $password
     * @param string $geoRadio
     * @param string $findnessSearchFee
     * @param SoapClient $client
     * @param EntityManager $em
     */
    public function __construct($username, $password, $geoRadio, $findnessSearchFee, SoapClient $client,
                                EntityManager $em)
    {
        parent::__construct($username, $password, $geoRadio, $findnessSearchFee, $client);
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    protected function store(array $companies)
    {
        foreach ($companies as $company) {
            $id = $company["id"];
            try {
                $this->em
                    ->getRepository("AppBundle:Company")
                    ->findOneByExternalId($id);
            } catch (NoResultException $exception) {
                $ormCompany = new Company();
                $ormCompany->setExternalId($id);
                $this->em->persist($ormCompany);
            }
        }
        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    protected function charge(array $companies, CustomerInterface $customer)
    {
        $companiesNotViewed = 0;
        foreach ($companies as $company) {
            if (!$company["viewed"]) {
                $companiesNotViewed++;
                $id = $company["id"];
                $company = $this->em
                    ->getRepository("AppBundle:Company")
                    ->findOneByExternalId($id);
                $customerViewCompany = new CustomerViewCompany();
                $customerViewCompany->setCustomer($customer);
                $customerViewCompany->setCompany($company);
                $this->em->persist($customerViewCompany);
            }
        }
        $transaction = parent::charge($companiesNotViewed, $customer);
        $ormTransaction = Transaction::fromBusinessEntity($transaction);
        $this->em->persist($ormTransaction);
        $balance = $this->em
            ->getRepository("AppBundle:Balance")
            ->findOneByCustomer($customer);
        $balance->setBalance($balance);
        $this->em->flush();
    }
}