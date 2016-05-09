<?php

namespace AppBundle\Services;

use AppBundle\Entity\Company;
use AppBundle\Entity\CustomerViewCompany;
use AppBundle\Entity\Transaction;
use BeSimple\SoapClient\SoapClient;
use Company\Qualitas\SOAPApi;
use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityManager;
use Finance\Finance\BalanceInterface;

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
     * Get customer balance
     *
     * @param CustomerInterface $customer
     * @return BalanceInterface
     */
    private function getBalance(CustomerInterface $customer)
    {
        return $this->em
            ->getRepository("AppBundle:Balance")
            ->findOneByCustomer($customer);
    }

    /**
     * @inheritdoc
     */
    protected function store(array $companies)
    {
        $ormCompanies = [];
        foreach ($companies as $id => $company) {
            $ormCompany = $this->em
                ->getRepository("AppBundle:Company")
                ->findOneByExternalId($id);

            if (!$ormCompany) {
                $ormCompany = new Company();
                $ormCompany->setExternalId($id);
                $this->em->persist($ormCompany);
            }

            $ormCompanies[$id] = $ormCompany;
        }
        $this->em->flush();

        return $ormCompanies;
    }

    /**
     * @inheritdoc
     */
    protected function charge(array $notViewedCompanies, CustomerInterface $customer)
    {
        foreach ($notViewedCompanies as $company) {
            $customerViewCompany = new CustomerViewCompany();
            $customerViewCompany->setCustomer($customer);
            $customerViewCompany->setCompany($company);
            $this->em->persist($customerViewCompany);
        }

        if (count($notViewedCompanies)) {
            $transaction = parent::charge($notViewedCompanies, $customer);

            $ormTransaction = Transaction::fromBusinessEntity($transaction);
            $balanceEntity = $this->getBalance($customer);
            $balanceEntity->setBalance($balanceEntity->getBalance() + $transaction->getBalance());
            $this->em->persist($ormTransaction);
            $this->em->flush();
            
            return $transaction;
        }
    }

    /**
     * @inheritdoc
     */
    public function query($page = 1,
                          $notViewedAllowedAmount = 0,
                          array $cnaes = [],
                          array $states = [],
                          array $cities = [],
                          array $postalCodes = [],
                          array $geoLocations = [],
                          CustomerInterface $customer)
    {
        set_time_limit(0);
        $notViewedAllowedAmount = floor($this->getBalance($customer)->getBalance() / $this->findnessSearchFee);
        return parent::query($page, $notViewedAllowedAmount, $cnaes, $states, $cities, $postalCodes, $geoLocations, $customer);
    }
}