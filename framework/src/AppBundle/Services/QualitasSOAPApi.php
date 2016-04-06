<?php

namespace AppBundle\Services;

use BeSimple\SoapClient\SoapClient;

/**
 * Class QualitasSOAPApi
 *
 * @package AppBundle\Services
 */
class QualitasSOAPApi
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
     * @var SoapClient
     */
    private $soapClient;

    /**
     * QualitasSOAPApi constructor.
     *
     * @param string $username
     * @param string $password
     * @param SoapClient $client
     */
    public function __construct($username, $password, SoapClient $client)
    {
        $this->username = $username;
        $this->password = $password;
        $this->soapClient = $client;
    }

    /**
     * Query API
     *
     * @return array
     */
    public function query()
    {
        $xmlRequest = '<SolicitudSegmentacion Id="1">
                    <Producto>SegmentacionListado</Producto>
                </SolicitudSegmentacion>';
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