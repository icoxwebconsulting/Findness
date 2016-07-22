<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MapRoute;
use AppBundle\Form\MapRouteType;
use AppBundle\ResponseObjects\MapRoute as MapRouteResponse;
use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class MapRouteController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class MapRoutesController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Create a new MapRoute
     *
     * @param Request $request
     * @return MapRouteResponse\Symfony\Component\Form\FormErrorIterator
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Create a new MapRoute",
     *  input="AppBundle\Form\MapRouteType",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function postAction(Request $request)
    {
        $mapRouteForm = $this->createForm(new MapRouteType());

        $mapRouteForm->handleRequest($request);

        if ($mapRouteForm->isValid()) {
            $mapRoute = new MapRoute();
            $customer = $this->getUser();
            $name = $mapRouteForm->get('name')->getData();
            $transport = $mapRouteForm->get('transport')->getData();
            $points = json_decode($mapRouteForm->get('points')->getData(), true);

            if (!is_array($points)) {
                throw new HttpException(500, 'points are not valid.');
            }

            $registrationHandler = $this->get('findness.mapRoute.registration');
            $response = $registrationHandler->register($mapRoute,
                $name,
                $transport,
                $customer,
                $points);
            return new MapRouteResponse($response);
        }

        return $mapRouteForm->getErrors();
    }

    /**
     * Get MapRoute's by customer
     *
     * @return array
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Get MapRoute's by Customer",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function cgetAction()
    {
        $customer = $this->getUser();
        $mapRoutes = $this->getDoctrine()
            ->getRepository("AppBundle:MapRoute")
            ->findByCustomer($customer);
        $routes = [];
        foreach ($mapRoutes as $route) {
            $routes[] = new MapRouteResponse($route);
        }
        return $routes;
    }

    /**
     * Get MapRoute
     *
     * @param MapRoute $mapRoute
     * @return array
     * @throws HttpException
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes/{mapRoute}")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Get MapRoute",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getAction(MapRoute $mapRoute = null)
    {
        if (!$mapRoute) {
            throw new HttpException(500, 'Map Route not found');
        }

        $registrationHandler = $this->get('findness.mapRoute.registration');
        return $registrationHandler->get($mapRoute);
    }

    /**
     * Update MapRoute
     *
     * @param MapRoute $mapRoute
     * @param Request $request
     * @return MapRoute
     * @throws HttpException
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes/{mapRoute}")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Update MapRoute",
     *  input="AppBundle\Form\MapRouteType",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function putAction(Request $request, MapRoute $mapRoute = null)
    {
        if (!$mapRoute) {
            throw new HttpException(500, 'Map Route not found');
        }

        $mapRouteForm = $this->createForm(new MapRouteType(), null, array('method' => 'PUT'));

        $mapRouteForm->handleRequest($request);

        if ($mapRouteForm->isValid()) {
            $name = $mapRouteForm->get('name')->getData();
            $transport = $mapRouteForm->get('transport')->getData();
            $points = json_decode($mapRouteForm->get('points')->getData(), true);

            if (!is_array($points)) {
                throw new HttpException(500, 'points are not valid.');
            }

            $registrationHandler = $this->get('findness.mapRoute.registration');
            $response = $registrationHandler->update($mapRoute,
                $name,
                $transport,
                $points);
            return new MapRouteResponse($response);
        }

        return $mapRouteForm->getErrors();
    }

    /**
     * Delete MapRoute
     *
     * @param MapRoute $mapRoute
     * @return boolean
     * @throws HttpException
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes/{mapRoute}")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Delete MapRoute",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function deleteAction(MapRoute $mapRoute = null)
    {
        if (!$mapRoute) {
            throw new HttpException(500, 'Map Route not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($mapRoute);
        $em->flush();

        return true;
    }
}
