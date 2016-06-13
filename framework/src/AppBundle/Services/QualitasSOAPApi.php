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
        $ids = [];
        foreach ($companies as $id => $company) {
            $ids[] = $id;
        }

        $qb = $this->em->createQueryBuilder();

        $ormCompanies = $qb->select('c')
            ->from('AppBundle:Company', 'c')
            ->where($qb->expr()->in('c.externalId', $ids))
            ->getQuery()
            ->getResult();

        $ormCompanies = array_reduce($ormCompanies, function ($previous, $current) {
            $previous[$current->getExternalId()] = $current;
            return $previous;
        }, []);

        foreach ($companies as $id => $company) {
            if (!array_key_exists($id, $ormCompanies)) {
                $ormCompany = new Company();
                $ormCompany->setExternalId($id);
                $ormCompany->setSocialReason($company["RazonSocial"]);
                $ormCompany->setSocialObject($company["ObjetoSocial"]);
                $ormCompany->setLatitude($company["Direccion"]["Latitud"]);
                $ormCompany->setLongitude($company["Direccion"]["Longitud"]);
                $this->em->persist($ormCompany);
                $ormCompanies[$id] = $ormCompany;
            }
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

        $balanceEntity = $this->getBalance($customer);

        if (count($notViewedCompanies)) {
            $transaction = parent::charge($notViewedCompanies, $customer);

            $ormTransaction = Transaction::fromBusinessEntity($transaction);
            $balanceEntity->setBalance($balanceEntity->getBalance() + $transaction->getBalance());
            $this->em->persist($ormTransaction);
        }

        $this->em->flush();

        return $balanceEntity;
    }

    /**
     * @param array $companies
     * @return array
     */
    protected function getAvailableStyles(array $companies)
    {
        $ids = array();
        foreach ($companies as $company) {
            $ids[] = $company["id"];
        }

        $qb = $this->em->createQueryBuilder();

        $styles = $qb
            ->select('sc')
            ->from('AppBundle:StyledCompany', 'sc')
            ->where($qb->expr()->in('sc.company', ':companyIds'))
            ->setParameter('companyIds', $ids)
            ->getQuery()
            ->getArrayResult();

        $styles = array_reduce($styles,
            function ($carry, $current) {
                $carry[$current["company"]] = $current["style"];
                return $carry;
            },
            array());

        return $styles;
    }

    /**
     * @param array $response
     * @return array
     */
    protected function applyStyles(array $response)
    {
        $response["items"] = array_reduce($response["items"],
            function ($carry, $current) {
                $carry[$current->getId()] = array(
                    "id" => $current->getId(),
                    "externalId" => $current->getExternalId(),
                    "socialReason" => $current->getSocialReason(),
                    "socialObject" => $current->getSocialObject(),
                    "latitude" => $current->getLatitude(),
                    "longitude" => $current->getLongitude()
                );
                return $carry;
            },
            array());

        $styles = $this->getAvailableStyles($response["items"]);

        foreach ($response["items"] as $key => $company) {
            if (array_key_exists($key, $styles)) {
                $response["items"][$key]["style"] = $styles[$key];
            }
        }

        return $response;
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
                          array $geoLocation = [],
                          CustomerInterface $customer)
    {
        set_time_limit(0);
        $available = floor($this->getBalance($customer)->getBalance() / $this->findnessSearchFee);
        if ($notViewedAllowedAmount === 0 || $available >= $notViewedAllowedAmount) {
            $response = parent::query($page, $notViewedAllowedAmount, $cnaes, $states, $cities, $postalCodes,
                $geoLocation, $customer);
            return $this->applyStyles($response);
        } else {
            return [
                "error" => "Not enough balance"
            ];
        }
    }
}