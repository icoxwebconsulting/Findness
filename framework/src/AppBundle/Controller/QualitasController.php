<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class QualitasController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class QualitasController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Register a new device
     *
     * @ApiDoc(
     *  section="Qualitas",
     *  description="Query Qualitas SOAP Service",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getAction()
    {
        $xmlRequest = '<SolicitudSegmentacion Id="1">
                    <Producto>SegmentacionListado</Producto>
                </SolicitudSegmentacion>';
        $request = [
            "nombreUsuario" => "alvaro535",
            "pwd" => "qkxb4huv",
            "peticionXml" => $xmlRequest
        ];

        $client = $this->container->get('besimple.soap.client.qualitasapi');
        $response = $client->AtenderPeticion($request);
        return $response->getAtenderPeticionResult();
    }
}