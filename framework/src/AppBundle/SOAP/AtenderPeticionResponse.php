<?php

namespace AppBundle\SOAP;

/**
 * Class AtenderPeticionResponse
 *
 * @package AppBundle\SOAP
 */
class AtenderPeticionResponse
{
    /**
     * @var string
     */
    private $AtenderPeticionResult;

    /**
     * Parse xml to object
     *
     * @param $xml
     * @return string
     */
    private function parseXML($xml)
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . $xml;
        return simplexml_load_string($xml);
    }

    /**
     * Parse Data
     *
     * @param string $data
     * @return array
     */
    private function parseData($data)
    {
        $result = [];
        $data = $data->ListaEmpresas->Empresa;
        if ($data) {
            foreach ($data as $item) {
                $direccionData = $item->Direccion->Direccion;
                $direccion = [
                    "CodInfotel" => $item->CodInfotel->__toString(),
                    "CodigoPostal" => $direccionData->CodigoPostal->__toString(),
                    "IdPais" => $direccionData->IdPais->__toString(),
                    "Pais" => $direccionData->Pais->__toString(),
                    "IdProvincia" => $direccionData->IdProvincia->__toString(),
                    "Provincia" => $direccionData->Provincia->__toString(),
                    "IdComunidadAutonoma" => $direccionData->IdComunidadAutonoma->__toString(),
                    "ComunidadAutonoma" => $direccionData->ComunidadAutonoma->__toString(),
                    "IdMunicipio" => $direccionData->IdMunicipio->__toString(),
                    "Municipio" => $direccionData->Municipio->__toString(),
                    "IdPoblacion" => $direccionData->IdPoblacion->__toString(),
                    "Poblacion" => $direccionData->Poblacion->__toString(),
                    "IdVia" => $direccionData->IdVia->__toString(),
                    "TipoVia" => $direccionData->TipoVia->__toString(),
                    "Via" => $direccionData->Via->__toString(),
                    "NumeroVia" => $direccionData->NumeroVia->__toString(),
                    "RestoVia" => $direccionData->RestoVia->__toString(),
                    "Latitud" => $direccionData->Latitud->__toString(),
                    "Longitud" => $direccionData->Longitud->__toString(),
                    "PrecisionCoordenadas" => $direccionData->PrecisionCoordenadas->__toString()
                ];

                $result[] = [
                    "CIF" => $item->CIF->__toString(),
                    "RazonSocial" => $item->RazonSocial->__toString(),
                    "Direccion" => $direccion,
                    "Telefono" => $item->Telefono->__toString(),
                ];
            }
        }
        return $result;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getAtenderPeticionResult()
    {
        $result = $this->parseXML($this->AtenderPeticionResult);
        if ($result) {
            return $this->parseData($result);
        } else {
            return "Bad Data";
        }
    }
}