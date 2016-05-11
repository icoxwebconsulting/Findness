<?php

namespace Company\Qualitas;

use BeSimple\SoapClient\SoapClient;
use Customer\Customer\CustomerInterface;
use Finance\Finance\TransactionInterface;
use Finance\Registration\RegistrationHandler;

/**
 * Class QualitasSOAPApi
 *
 * @package Company\Qualitas
 */
abstract class SOAPApi
{

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $geoRadio;

    /**
     * @var string
     */
    protected $findnessSearchFee;

    /**
     * @var SoapClient
     */
    protected $soapClient;

    /**
     * QualitasSOAPApi constructor.
     *
     * @param string $username
     * @param string $password
     * @param string $geoRadio
     * @param string $findnessSearchFee
     * @param SoapClient $client
     */
    public function __construct($username, $password, $geoRadio, $findnessSearchFee, SoapClient $client)
    {
        $this->username = $username;
        $this->password = $password;
        $this->geoRadio = $geoRadio;
        $this->findnessSearchFee = $findnessSearchFee;
        $this->soapClient = $client;
    }

    /**
     * Get Cnae filter
     *
     * @param array $cnaes
     * @return string
     */
    protected function getCnaeFilter(array $cnaes = [])
    {
        if (!$cnaes) {
            return "";
        }

        $filter = "<ListaCnaes>%s</ListaCnaes>";

        foreach ($cnaes as $cnae) {
            $filter = sprintf($filter, sprintf("<Cnae>%s</Cnae>%%s", $cnae));
        }

        $filter = str_replace("</Cnae>%s", "</Cnae>", $filter);

        return $filter;
    }

    /**
     * Get State filter
     *
     * @param array $states
     * @return string
     */
    protected function getStateFilter(array $states = [])
    {
        if (!$states) {
            return "";
        }

        $filter = "<ListaProvincias>%s</ListaProvincias>";

        foreach ($states as $state) {
            $filter = sprintf($filter, sprintf("<CodProvincia>%s</CodProvincia>%%s", $state));
        }

        $filter = str_replace("</CodProvincia>%s", "</CodProvincia>", $filter);

        return $filter;
    }

    /**
     * Get City filter
     *
     * @param array $cities
     * @return string
     */
    protected function getCityFilter(array $cities = [])
    {
        if (!$cities) {
            return "";
        }

        $state = $cities["state"];
        $cities = $cities["cities"];
        $filter = "<ListaMunicipios>%s</ListaMunicipios>";

        foreach ($cities as $city) {
            $filter = sprintf($filter, sprintf('<CodMunicipio CodProvincia="%s">%s</CodMunicipio>%%s', $state, $city));
        }

        $filter = str_replace("</CodMunicipio>%s", "</CodMunicipio>", $filter);

        return $filter;
    }

    /**
     * Get Postal Code filter
     *
     * @param array $postalCodes
     * @return string
     */
    protected function getPostalCodeFilter(array $postalCodes = [])
    {
        if (!$postalCodes) {
            return "";
        }

        $filter = "<ListaCodigosPostales>%s</ListaCodigosPostales>";

        foreach ($postalCodes as $postalCode) {
            $filter = sprintf($filter, sprintf('<CodPostal>%s</CodPostal>%%s', $postalCode));
        }

        $filter = str_replace("</CodPostal>%s", "</CodPostal>", $filter);

        return $filter;
    }

    /**
     * Get Geo filter
     *
     * @param array $geoLocations
     * @return string
     */
    protected function getGeoFilter(array $geoLocations = [])
    {
        if (!$geoLocations) {
            return "";
        }

        $filter = "<Geografia Tipo=“Buffer”><Coordenadas>%%s</Coordenadas><Radio>%s</Radio></Geografia>";
        $filter = sprintf($filter, $this->geoRadio);

        foreach ($geoLocations as $geoLocation) {
            $filter = sprintf($filter, sprintf('<Latitud>%s</Latitud><Longitud>%s</Longitud>%%s',
                $geoLocation->latitude,
                $geoLocation->longitude));
        }

        $filter = str_replace("</Longitud>%s", "</Longitud>", $filter);

        return $filter;
    }

    /**
     * Get XML filters
     *
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocations
     * @return string
     */
    protected function getFilters(array $cnaes = [],
                                  array $states = [],
                                  array $cities = [],
                                  array $postalCodes = [],
                                  array $geoLocations = [])
    {
        $filters = "";
        $filters .= $this->getCnaeFilter($cnaes);
        $filters .= $this->getStateFilter($states);
        $filters .= $this->getCityFilter($cities);
        $filters .= $this->getPostalCodeFilter($postalCodes);
        $filters .= $this->getPostalCodeFilter($postalCodes);
        $filters .= $this->getGeoFilter($geoLocations);
        return $filters;
    }

    /**
     * Build query XML
     *
     * @param CustomerInterface $customer
     * @param int $page
     * @param int $notViewedAllowedAmount
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocations
     * @return string
     */
    protected function buildQueryXML(CustomerInterface $customer,
                                     $page = 1,
                                     $notViewedAllowedAmount = 0,
                                     array $cnaes = [],
                                     array $states = [],
                                     array $cities = [],
                                     array $postalCodes = [],
                                     array $geoLocations = [])
    {
        $xmlRequest = '<SolicitudSegmentacion Pagina="%s"><Producto>%s</Producto><ComentarioLibre>%s</ComentarioLibre><MaximoElementosNoDevueltos>%s</MaximoElementosNoDevueltos>%s</SolicitudSegmentacion>';

        $filters = $this->getFilters($cnaes, $states, $cities, $postalCodes, $geoLocations);
        $xmlRequest = sprintf($xmlRequest, $page, "SegmentacionListadoThinkandCloud", $customer->getId(),
            $notViewedAllowedAmount, $filters);

        return $xmlRequest;
    }

    /**
     * Store companies in the response that are new to findness database
     *
     * @param array $companies
     * @return array
     */
    protected abstract function store(array $companies);

    /**
     * Charge customer for non paid companies
     *
     * @param array $notViewedCompanies
     * @param CustomerInterface $customer
     * @return TransactionInterface|null
     */
    protected function charge(array $notViewedCompanies, CustomerInterface $customer)
    {
        $apisConf = ["findness" => []];
        $transactionRegistration = new RegistrationHandler();
        $transaction = $transactionRegistration->charge($customer, count($notViewedCompanies),
            $this->findnessSearchFee, $apisConf);

        return $transaction;
    }

    /**
     * Query API
     *
     * @param int $page
     * @param int $notViewedAllowedAmount
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocations
     * @param CustomerInterface $customer
     * @return array
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
        $xmlRequest = $this->buildQueryXML($customer, $page, $notViewedAllowedAmount, $cnaes, $states, $cities,
            $postalCodes, $geoLocations);
        $request = [
            "nombreUsuario" => $this->username,
            "pwd" => $this->password,
            "peticionXml" => $xmlRequest
        ];

        $client = $this->soapClient;
        $response = $client->AtenderPeticion($request);
        $result = $response->getAtenderPeticionResult();
        $companies = $result["items"];

        $storedCompanies = [];

        if ($companies) {
            $storedCompanies = $this->store($companies);
            $notViewedCompanies = [];
            foreach ($storedCompanies as $id => $company) {
                if (!$companies[$id]["Consultada"]) {
                    $notViewedCompanies[] = $storedCompanies[$id];
                }
            }
            $this->charge($notViewedCompanies, $customer);
            $result["items"] = $storedCompanies;
        }

        return $result;
    }
}