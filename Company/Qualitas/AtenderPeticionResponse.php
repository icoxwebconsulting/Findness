<?php

namespace Company\Qualitas;

/**
 * Class AtenderPeticionResponse
 *
 * @package Company\Qualitas
 */
class AtenderPeticionResponse
{
    /**
     * @var string
     */
    protected $AtenderPeticionResult;

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
        foreach ($data->ListaEmpresas->attributes() as $attribute => $value) {
            $result[$attribute] = (int)$value;
        }

        $companies = [];
        $empresas = $data->ListaEmpresas->Empresas;
        if ($empresas) {
            foreach ($empresas as $empresa) {
                $direccionData = $empresa->Direccion->Direccion;
                $direccion = [
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

                $id = $empresa->CodInfotel->__toString();

                $consultada = false;
                foreach ($empresa->attributes() as $name => $value) {
                    if ($name === "Consultada") {
                        $consultada = (string)$value === "true" ? true : false;
                    }
                }

                $companies[$id] = [
                    "id" => $id,
                    "Consultada" => $consultada,
                    "CIF" => $empresa->CIF->__toString(),
                    "RazonSocial" => $empresa->RazonSocial->__toString(),
                    "ObjetoSocial" => $empresa->ObjetoSocial->__toString(),
                    "Direccion" => $direccion,
                    "Telefono" => $empresa->Telefono->__toString(),
                    "TotalEmpleados" => $empresa->Empleados->__toString(),
                    "Facturacion" => $empresa->Ventas->__toString(),
                    "EsAutonomo" => $empresa->EsAutonomo->__toString(),
                    "LetraSector" => $empresa->LetraSector->__toString(),
                    "Cnae" => $empresa->Cnae->__toString(),
                ];
            }
        }

        $result["items"] = $companies;

        return $result;
    }

    /**
     * Get result
     *
     * @return array
     * @throws \Exception
     */
    public function getAtenderPeticionResult()
    {
        $result = $this->parseXML($this->AtenderPeticionResult);

        if ($result && !$result->CodigoError) {
            /*echo "<pre>";
            print_r($this->parseData($result));
            echo "</pre>";
            die('resultados');*/
            return $this->parseData($result);
        } else {
            throw new \Exception('Error on request');
        }
    }
}