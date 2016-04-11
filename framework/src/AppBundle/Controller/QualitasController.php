<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

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
     * Search on Qualitas api
     *
     * @param Request $request
     * @return array
     *
     * @ApiDoc(
     *  section="Qualitas",
     *  description="Query Qualitas SOAP Service",
     *  parameters={
     *     {
     *          "name"="page",
     *          "dataType"="integer",
     *          "requirement"="[0-9]+",
     *          "required"=false,
     *          "description"="page pagination"
     *      },
     *     {
     *          "name"="cnaes",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "required"=false,
     *          "description"="json string of cnaes array"
     *      },
     *     {
     *          "name"="states",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "required"=false,
     *          "description"="json string of states array"
     *      },
     *     {
     *          "name"="cities",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "required"=false,
     *          "description"="json string of cities array"
     *      },
     *     {
     *          "name"="postalCodes",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "required"=false,
     *          "description"="json string of postal codes array"
     *      },
     *     {
     *          "name"="geoLocations",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "required"=false,
     *          "description"="json string of geo locations array"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getAction(Request $request)
    {
        $page = $request->get("page", 1);
        $cnaes = json_decode($request->get("cnaes", json_encode([])));
        $states = json_decode($request->get("states", json_encode([])));
        $citiesObj = json_decode($request->get("cities", json_encode([])), JSON_FORCE_OBJECT);
        $cities = [];
        if (is_object($citiesObj) && property_exists($citiesObj, "state") && property_exists($citiesObj, "cities")) {
            $cities = ["state" => $citiesObj->state, "cities" => $citiesObj->cities];
        }
        $postalCodes = json_decode($request->get("postalCodes", json_encode([])));
        $geoLocations = json_decode($request->get("geoLocations", json_encode([])));
        $qualitas = $this->container->get('findness.qualitas');
        return $qualitas->query($page, $cnaes, $states, $cities, $postalCodes, $geoLocations);
    }
}