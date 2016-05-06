<?php

namespace AppBundle\Services;

use BeSimple\SoapClient\SoapClient;
use Company\Qualitas\SOAPApi;
use Doctrine\ORM\EntityManager;

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
     * @param SoapClient $client
     * @param EntityManager $em
     */
    public function __construct($username, $password, $geoRadio, SoapClient $client, EntityManager $em)
    {
        parent::__construct($username, $password, $geoRadio, $client);
        $this->em = $em;
    }

    /**
     * Charge customer for non paid companies
     *
     * @param array $companies
     */
    private function charge(array $companies)
    {
        foreach ($companies as $company) {
            
        }
        $this->em->flush();
    }

    /**
     * Query API
     *
     * @param int $page
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocations
     * @return array
     */
    public function query($page = 1,
                          array $cnaes = [],
                          array $states = [],
                          array $cities = [],
                          array $postalCodes = [],
                          array $geoLocations = [])
    {
        $companies = parent::query($page, $cnaes, $states, $cities, $postalCodes, $geoLocations);
        $this->charge($companies);
        return $companies;
    }
}