<?php

namespace AppBundle\Services;

use AppBundle\Entity\Company;
use AppBundle\Entity\CustomerViewCompany;
use AppBundle\Entity\Search;
use AppBundle\Entity\StaticList;
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
     * @param $username
     * @param $password
     * @param $geoRadio
     * @param $findnessSearchFee
     * @param $findnessSearchMin
     * @param $findnessSearchExtraFee
     * @param $findnessSearchExtraFeeThreshold
     * @param $findnessSearchIvaFee
     * @param SoapClient $client
     * @param EntityManager $em
     */
    public function __construct($username, $password, $geoRadio, $findnessSearchFee, $findnessSearchMin,
                                $findnessSearchExtraFee, $findnessSearchExtraFeeThreshold, $findnessSearchIvaFee,
                                SoapClient $client, EntityManager $em)
    {
        parent::__construct($username, $password, $geoRadio, $findnessSearchFee, $findnessSearchMin,
            $findnessSearchExtraFee, $findnessSearchExtraFeeThreshold, $findnessSearchIvaFee, $client);
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
    protected function store(array $companies, CustomerInterface $customer)
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
                $ormCompany->setCIF($company["CIF"]);
                $ormCompany->setPhoneNumber($company["Telefono"]);
                $ormCompany->setAddress(sprintf("%s %s %s %s %s %s %s",
                    $company["Direccion"]["TipoVia"],
                    $company["Direccion"]["Via"],
                    $company["Direccion"]["Poblacion"],
                    $company["Direccion"]["Municipio"],
                    $company["Direccion"]["ComunidadAutonoma"],
                    $company["Direccion"]["Provincia"],
                    $company["Direccion"]["Pais"]));
                $this->em->persist($ormCompany);
                $ormCompanies[$id] = $ormCompany;
            }
        }

        $qb = $this->em->createQueryBuilder();

        $viewedCompanies = $qb->select('cvc')
            ->from('AppBundle:CustomerViewCompany', 'cvc')
            ->where($qb->expr()->in('cvc.company', $ids))
            ->andWhere('cvc.customer = :customer')
            ->setParameter('customer', $customer->getId())
            ->getQuery()
            ->getResult();

        $viewedCompanies = array_reduce($viewedCompanies, function ($previous, $current) {
            $previous[] = $current->getCompany()->getId();
            return $previous;
        }, []);

        foreach ($ormCompanies as $company) {
            if (!in_array($company->getId(), $viewedCompanies)) {
                $customerViewCompany = new CustomerViewCompany();
                $customerViewCompany->setCustomer($customer);
                $customerViewCompany->setCompany($company);
                $this->em->persist($customerViewCompany);
            }
        }

        $this->em->flush();

        return $ormCompanies;
    }

    /**
     * @inheritdoc
     */
    protected function charge(CustomerInterface $customer, $balance)
    {
        $balanceEntity = $this->getBalance($customer);

        $transaction = parent::charge($customer, $balance);

        $ormTransaction = Transaction::fromBusinessEntity($transaction);
        $balanceEntity->setBalance($balanceEntity->getBalance() + $transaction->getBalance());
        $this->em->persist($ormTransaction);

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
                    "longitude" => $current->getLongitude(),
                    "cif" => $current->getCIF(),
                    "address" => $current->getAddress(),
                    "phoneNumber" => $current->getPhoneNumber()
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
     * @param CustomerInterface $customer
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocation
     */
    private function saveSearch(CustomerInterface $customer,
                                array $cnaes = [],
                                array $states = [],
                                array $cities = [],
                                array $postalCodes = [],
                                array $geoLocation = [])
    {
        $filters = array(
            'cnaes' => $cnaes,
            'states' => $states,
            'cities' => $cities,
            'postalCodes' => $postalCodes,
            'geoLocation' => $geoLocation
        );
        $name = sprintf('%s#%s', $cnaes[0], date("Y-m-d@H:i:s"));
        $search = new Search();
        $search->setName($name);
        $search->setFilters($filters);
        $search->setCustomer($customer);
        $this->em->persist($search);
        $this->em->flush();
    }

    /**
     * @param CustomerInterface $customer
     * @param array $cnaes
     * @param array $companies
     */
    private function saveList(CustomerInterface $customer,
                              array $cnaes = [],
                              array $companies = [])
    {
        $name = sprintf('%s#%s', $cnaes[0], date("Y-m-d@H:i:s"));
        $staticList = new StaticList($customer, $name);
        $staticList->setCompanies($companies);
        $this->em->persist($staticList);
        $this->em->flush();
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
                          CustomerInterface $customer,
                          $balance = 0)
    {
        if (empty($cnaes)) {
            return [
                "error" => "You need to specify a CNAE"
            ];
        }

        set_time_limit(0);
        ini_set('memory_limit', '4048M');
        try {
            $balance = $this->getBalance($customer)->getBalance();

            $response = parent::query($page, $notViewedAllowedAmount, $cnaes, $states, $cities, $postalCodes,
                $geoLocation, $customer, $balance);

            if ($notViewedAllowedAmount) {
                $this->saveSearch($customer, $cnaes, $states, $cities, $postalCodes, $geoLocation);
                $this->saveList($customer, $cnaes, $response["items"]);
            }

            return $this->applyStyles($response);
        } catch (\Exception $exception) {
            return [
                'error' => $exception->getMessage()
            ];
        }
    }
}