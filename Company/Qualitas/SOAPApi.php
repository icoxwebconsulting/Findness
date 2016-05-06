<?php

namespace Company\Qualitas;

use BeSimple\SoapClient\SoapClient;

/**
 * Class QualitasSOAPApi
 *
 * @package Company\Qualitas
 */
class SOAPApi
{

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $geoRadio;

    /**
     * @var SoapClient
     */
    private $soapClient;

    /**
     * QualitasSOAPApi constructor.
     *
     * @param string $username
     * @param string $password
     * @param string $geoRadio
     * @param SoapClient $client
     */
    public function __construct($username, $password, $geoRadio, SoapClient $client)
    {
        $this->username = $username;
        $this->password = $password;
        $this->geoRadio = $geoRadio;
        $this->soapClient = $client;
    }

    /**
     * Get Cnae filter
     *
     * @param array $cnaes
     * @return string
     */
    private function getCnaeFilter(array $cnaes = [])
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
    private function getStateFilter(array $states = [])
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
    private function getCityFilter(array $cities = [])
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
    private function getPostalCodeFilter(array $postalCodes = [])
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
    private function getGeoFilter(array $geoLocations = [])
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
    private function getFilters(array $cnaes = [],
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
     * @param int $page
     * @param array $cnaes
     * @param array $states
     * @param array $cities
     * @param array $postalCodes
     * @param array $geoLocations
     * @return string
     */
    private function buildQueryXML($page = 1,
                                   array $cnaes = [],
                                   array $states = [],
                                   array $cities = [],
                                   array $postalCodes = [],
                                   array $geoLocations = [])
    {
        $xmlRequest = '<SolicitudSegmentacion Pagina="%s"><Producto>SegmentacionListadoThinkandCloud</Producto>%s</SolicitudSegmentacion>';

        $filters = $this->getFilters($cnaes, $states, $cities, $postalCodes, $geoLocations);
        $xmlRequest = sprintf($xmlRequest, $page, $filters);

        return $xmlRequest;
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
        $xmlRequest = $this->buildQueryXML($page, $cnaes, $states, $cities, $postalCodes, $geoLocations);
        $request = [
            "nombreUsuario" => $this->username,
            "pwd" => $this->password,
            "peticionXml" => $xmlRequest
        ];

        $client = $this->soapClient;
        $response = $client->AtenderPeticion($request);
        return $response->getAtenderPeticionResult();
    }
}