<?php

namespace Company\Qualitas;

use BeSimple\SoapClient\SoapClient;
use Customer\Customer\CustomerInterface;
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
     * @var string
     */
    protected $findnessSearchMin;

    /**
     * @var string
     */
    protected $findnessSearchExtraFee;

    /**
     * @var string
     */
    protected $findnessSearchExtraFeeThreshold;

    /**
     * @var string
     */
    protected $findnessSearchIvaFee;

    /**
     * @var SoapClient
     */
    protected $soapClient;

    /**
     * SOAPApi constructor.
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
     */
    public function __construct(
        $username,
        $password,
        $geoRadio,
        $findnessSearchFee,
        $findnessSearchMin,
        $findnessSearchExtraFee,
        $findnessSearchExtraFeeThreshold,
        $findnessSearchIvaFee,
        SoapClient $client
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->geoRadio = $geoRadio;
        $this->findnessSearchFee = $findnessSearchFee;
        $this->findnessSearchMin = $findnessSearchMin;
        $this->findnessSearchExtraFee = $findnessSearchExtraFee;
        $this->findnessSearchExtraFeeThreshold = $findnessSearchExtraFeeThreshold;
        $this->findnessSearchIvaFee = $findnessSearchIvaFee;
        $this->soapClient = $client;
    }

    /**
     * Query API by Balance
     *
     * @param int $page
     * @param int $notViewedAllowedAmount
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocation
     * @param CustomerInterface $customer
     * @param $balance
     * @param $sector
     * @return array
     */
    public function queryByBalance(
        $page = 1,
        $notViewedAllowedAmount = 0,
        array $cnaes = [],
        array $states = [],
        array $cities = [],
        array $postalCodes = [],
        array $geoLocation = [],
        CustomerInterface $customer,
        $balance = 0,
        $sector = ''
    ) {
        $this->computeBalance((int)$notViewedAllowedAmount, $balance);

        $xmlRequest = $this->buildQueryXML(
            $customer,
            $page,
            $notViewedAllowedAmount,
            $cnaes,
            $states,
            $cities,
            $postalCodes,
            $geoLocation,
            $sector
        );

        $request = [
            "nombreUsuario" => $this->username,
            "pwd" => $this->password,
            "peticionXml" => $xmlRequest,
        ];

        $client = $this->soapClient;
        $response = $client->AtenderPeticion($request);
        $result = $response->getAtenderPeticionResult();
        $companies = $result["items"];

        if ($companies) {
            $storedCompanies = $this->store($companies, $customer);
            $notViewedCompanies = [];
            foreach ($storedCompanies as $id => $company) {
                if (!$companies[$id]["Consultada"]) {
                    $notViewedCompanies[] = $storedCompanies[$id];
                }
            }

            if (count($notViewedCompanies)) {
                $balance = $this->computeBalance(count($notViewedCompanies), $balance);
                $balance = $this->charge($customer, $balance);
            }

            $result["items"] = $storedCompanies;
            $result["balance"] = $balance;
        }

        return $result;
    }

    /**
     * @param int $notViewedCompanies
     * @param $balance
     * @return int
     */
    protected function computeBalance($notViewedCompanies, $balance)
    {
        $transactionRegistration = new RegistrationHandler();

        return $transactionRegistration->computeBalance(
            $notViewedCompanies,
            $this->findnessSearchFee,
            $this->findnessSearchMin,
            $this->findnessSearchExtraFee,
            $this->findnessSearchExtraFeeThreshold,
            $this->findnessSearchIvaFee,
            $balance
        );
    }

    /**
     * @param CustomerInterface $customer
     * @param int $page
     * @param int $notViewedAllowedAmount
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocations
     * @param bool $billingMin
     * @param bool $billingMax
     * @param bool $employeesMin
     * @param bool $employeesMax
     * @param string $sector
     * @return string
     */
    protected function buildQueryXML(
        CustomerInterface $customer,
        $page = 1,
        $notViewedAllowedAmount = 0,
        array $cnaes = [],
        array $states = [],
        array $cities = [],
        array $postalCodes = [],
        array $geoLocations = [],
        $billingMin = false,
        $billingMax = false,
        $employeesMin = false,
        $employeesMax = false,
        $sector = ''
    ) {
        $xmlRequest = '<SolicitudSegmentacion Pagina="%s"><Producto>%s</Producto><ComentarioLibre>%s</ComentarioLibre><MaximoElementosNoDevueltos>%s</MaximoElementosNoDevueltos>%s</SolicitudSegmentacion>';

        $filters = $this->getFilters($cnaes, $states, $cities, $postalCodes, $geoLocations,$billingMin, $billingMax, $employeesMin, $employeesMax, $sector);
        $xmlRequest = sprintf(
            $xmlRequest,
            $page,
            "SegmentacionListadoThinkandCloud",
            $customer->getId(),
            $notViewedAllowedAmount,
            $filters
        );

        /*echo "<pre>";
        print_r($xmlRequest);
        echo "</pre>";
        die('resultados');*/

        return $xmlRequest;
    }

    /**
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocations
     * @param bool $billingMin
     * @param bool $billingMax
     * @param bool $employeesMin
     * @param bool $employeesMax
     * @param string $sector
     * @return string
     */
    protected function getFilters(
        array $cnaes = [],
        array $states = [],
        array $cities = [],
        array $postalCodes = [],
        array $geoLocations = [],
        $billingMin = false,
        $billingMax = false,
        $employeesMin = false,
        $employeesMax = false,
        $sector = ''
    ) {
        $filters = "";
        $filters .= $this->getCnaeFilter($cnaes);
        $filters .= $this->getBillingFilter($billingMin, $billingMax);
        $filters .= $this->getEmployeesFilter($employeesMin,$employeesMax);
        $filters .= $this->getSectorFilter($sector);
        $filters .= $this->getStateFilter($states);
        $filters .= $this->getCityFilter($cities);
        $filters .= $this->getPostalCodeFilter($postalCodes);
        $filters .= $this->getGeoFilter($geoLocations);


        return $filters;
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
     * @param $min
     * @param $max
     * @return mixed|string
     */
    protected function getBillingFilter($min, $max)
    {
        if (!$max && !$min){
            return '';
        }


        $filter = "<Facturacion>%s</Facturacion>";

        if ($min){
            $filter = sprintf($filter, sprintf('<Minimo>%s</Minimo>%%s', $min));
        }

        if ($max){
            $filter = sprintf($filter, sprintf('<Maximo>%s</Maximo>%%s', $max));
        }

        if ($min){
            $filter = str_replace("</Minimo>%s", "</Minimo>", $filter);
        }

        if ($max){
            $filter = str_replace("</Maximo>%s", "</Maximo>", $filter);
        }

        return $filter;
    }


    /**
     * @param $min
     * @param $max
     * @return mixed|string
     */
    protected function getEmployeesFilter($min, $max)
    {

        if ($min == false || $max == false) {
            return "";
        }

        $filter = "<TotalEmpleados>%s</TotalEmpleados>";

        if ($min){
            $filter = sprintf($filter, sprintf('<Minimo>%s</Minimo>%%s', $min));
        }

        if ($max){
            $filter = sprintf($filter, sprintf('<Maximo>%s</Maximo>%%s', $max));
        }

        if ($min){
            $filter = str_replace("</Minimo>%s", "</Minimo>", $filter);
        }

        if ($max){
            $filter = str_replace("</Maximo>%s", "</Maximo>", $filter);
        }


        return $filter;

    }

    /**
     * @param $sector
     * @return string
     */
    protected function getSectorFilter($sector)
    {

        if (empty($sector)) {
            return "";
        }

        $filter = "<ListaSector>%s</ListaSector>";
        $filter = sprintf($filter, sprintf('<Sector>%s</Sector>%%s', $sector));
        $filter = str_replace("</Sector>%s", "</Sector>", $filter);


        return $filter;

    }

    /**
     * Get Geo filter
     *
     * @param array $geoLocation
     * @return string
     */
    protected function getGeoFilter(array $geoLocation = [])
    {
        if (empty($geoLocation)) {
            return "";
        }

        $filter = "<Geografia><Coordenadas><Latitud>%s</Latitud><Longitud>%s</Longitud></Coordenadas><Radio>%s</Radio></Geografia>";
        $filter = sprintf(
            $filter,
            $geoLocation["latitude"],
            $geoLocation["longitude"],
            $geoLocation["radio"]
        );

        return $filter;
    }

    /**
     * Store companies in the response that are new to findness database
     *
     * @param array $companies
     * @param CustomerInterface $customer
     * @return array
     */
    protected abstract function store(array $companies, CustomerInterface $customer);

    /**
     * Charge customer for non paid companies
     *
     * @param CustomerInterface $customer
     * @param $balance
     * @return mixed|null
     */
    protected function charge(CustomerInterface $customer, $balance)
    {
        $apisConf = ["findness" => []];
        $transactionRegistration = new RegistrationHandler();

        return $transactionRegistration->charge($customer, $balance, $apisConf);
    }

    /**
     * @param int $page
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocation
     * @param CustomerInterface $customer
     * @param int $billingMin
     * @param int $billingMax
     * @param int $employeesMin
     * @param int $employeesMax
     * @param string $sector
     * @return mixed
     */
    public function query(
        $page = 1,
        array $cnaes = [],
        array $states = [],
        array $cities = [],
        array $postalCodes = [],
        array $geoLocation = [],
        CustomerInterface $customer,
        $billingMin = null,
        $billingMax = null,
        $employeesMin = null,
        $employeesMax = null,
        $sector = ''
    ) {
        $xmlRequest = $this->buildQueryXML(
            $customer,
            $page,
            100000,
            $cnaes,
            $states,
            $cities,
            $postalCodes,
            $geoLocation,
            $billingMin,
            $billingMax,
            $employeesMin,
            $employeesMax,
            $sector
        );

        $request = [
            "nombreUsuario" => $this->username,
            "pwd" => $this->password,
            "peticionXml" => $xmlRequest,
        ];

        $client = $this->soapClient;
        $response = $client->AtenderPeticion($request);
        $result = $response->getAtenderPeticionResult();
        $companies = $result["items"];

        if ($companies) {
            $result["items"] = $this->store($companies, $customer);
        }

        return $result;
    }
}