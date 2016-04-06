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
        foreach ($data as $item) {
            $id = $item->attributes()->IdEmpresa->__toString();
            $result[$id] = [
                "CIF" => $item->CIF->__toString(),
                "RazonSocial" => $item->RazonSocial->__toString(),
                "ObjetoSocial" => $item->ObjetoSocial->__toString(),
                "Cnae" => $item->Cnae->__toString(),
                "CodProvincia" => $item->CodProvincia->__toString(),
                "CodMunicipio" => $item->CodMunicipio->__toString(),
                "EjercicioDatosFinancieros" => $item->EjercicioDatosFinancieros->__toString(),
                "ImporteNetoCifraNegocios" => $item->ImporteNetoCifraNegocios->__toString(),
                "EBITDA" => $item->EBITDA->__toString(),
                "EBIT" => $item->EBIT->__toString(),
                "ResultadoEjercicio" => $item->ResultadoEjercicio->__toString(),
                "ActivoNoCorriente" => $item->ActivoNoCorriente->__toString(),
                "ActivoCorriente" => $item->ActivoCorriente->__toString(),
                "FondosPropios" => $item->FondosPropios->__toString(),
                "PasivoNoCorriente" => $item->PasivoNoCorriente->__toString(),
                "Deudas" => $item->Deudas->__toString(),
                "Empleados" => $item->Empleados->__toString()
            ];
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