<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MapRoute;
use AppBundle\Entity\MapRoutePath;
use AppBundle\Form\MapRoutePathType;
use AppBundle\Form\MapRouteType;
use AppBundle\ResponseObjects\MapRoute as MapRouteResponse;
use AppBundle\ResponseObjects\MapRoutePath as MapRoutePathResponse;
use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use MapRoute\MapRoutePath\MapRoutePathInterface;
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
class MapRouteController extends FOSRestController implements ClassResourceInterface
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
            $registrationHandler = $this->get('findness.mapRoute.registration');
            $response = $registrationHandler->register($mapRoute,
                $name,
                $transport,
                $customer);
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
            $routes[] = [
                "id" => $route->getId(),
                "name" => $route->getName(),
                "transport" => $route->getTransport()
            ];
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

        return [
            "id" => $mapRoute->getId(),
            "name" => $mapRoute->getName(),
            "transport" => $mapRoute->getTransport()
        ];
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
            $registrationHandler = $this->get('findness.mapRoute.registration');
            $response = $registrationHandler->update($mapRoute,
                $name,
                $transport);
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

    /**
     * Create a new MapRoute Path
     *
     * @param MapRoute $mapRoute
     * @param Request $request
     * @return MapRoutePathResponse\Symfony\Component\Form\FormErrorIterator
     * @throws HttpException
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes/{mapRoute}")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Create a new MapRoute Path",
     *  input="AppBundle\Form\MapRoutePathType",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Returned on not found map route"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function postPathAction(MapRoute $mapRoute, Request $request)
    {
        if (!$mapRoute) {
            throw new HttpException(500, 'Map Route not found');
        }

        $mapRoutePathForm = $this->createForm(new MapRoutePathType());

        $mapRoutePathForm->handleRequest($request);

        if ($mapRoutePathForm->isValid()) {
            $mapRoutePath = new MapRoutePath();
            $startPoint = $mapRoutePathForm->get('startPoint')->getData();
            $endPoint = $mapRoutePathForm->get('endPoint')->getData();
            $registrationHandler = $this->get('findness.mapRoute.registration');
            $response = $registrationHandler->registerPath($mapRoute,
                $mapRoutePath,
                $startPoint,
                $endPoint);
            return new MapRoutePathResponse($response);
        }

        return $mapRoutePathForm->getErrors();
    }

    /**
     * Get MapRoute Path's by MapRoute
     *
     * @param MapRoute $mapRoute
     * @return array
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes/{mapRoute}/paths")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Get MapRoute Path's by MapRoute",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Returned on not found map route"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function cgetPathsAction(MapRoute $mapRoute)
    {
        if (!$mapRoute) {
            throw new HttpException(500, 'Map Route not found');
        }

        $mapRoutePaths = $this->getDoctrine()
            ->getRepository("AppBundle:MapRoutePath")
            ->findByMapRoute($mapRoute);
        $paths = [];
        foreach ($mapRoutePaths as $path) {
            $paths[] = [
                "id" => $path->getId(),
                "startPoint" => $path->getStartPoint(),
                "endPoint" => $path->getEndPoint()
            ];
        }
        return $paths;
    }

    /**
     * Get MapRoute Path
     *
     * @param MapRoute $mapRoute
     * @param MapRoutePath $mapRoutePath
     * @return MapRoutePathInterface
     * @throws HttpException
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes/{mapRoute}/path/{mapRoutePath}")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Get MapRoute Path",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Returned on not found map route",
     *         500="Returned on not found map route path"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getPathAction(MapRoute $mapRoute = null, MapRoutePath $mapRoutePath = null)
    {
        if (!$mapRoute) {
            throw new HttpException(500, 'Map Route not found');
        }

        if (!$mapRoutePath) {
            throw new HttpException(500, 'Map Route Path not found');
        }

        return [
            "id" => $mapRoutePath->getId(),
            "startPoint" => $mapRoutePath->getStartPoint(),
            "endPoint" => $mapRoutePath->getEndPoint()
        ];
    }

    /**
     * Update MapRoute Path
     *
     * @param Request $request
     * @param MapRoute $mapRoute
     * @param MapRoutePath $mapRoutePath
     * @return MapRoutePathInterface
     * @throws HttpException
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes/{mapRoute}/path/{mapRoutePath}")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Update MapRoute",
     *  input="AppBundle\Form\MapRoutePathType",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Returned on not found map route",
     *         500="Returned on not found map route path"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function putPathAction(Request $request, MapRoute $mapRoute = null, MapRoutePath $mapRoutePath = null)
    {
        if (!$mapRoute) {
            throw new HttpException(500, 'Map Route not found');
        }

        if (!$mapRoutePath) {
            throw new HttpException(500, 'Map Route Path not found');
        }

        $mapRoutePathForm = $this->createForm(new MapRoutePathType(), null, array('method' => 'PUT'));

        $mapRoutePathForm->handleRequest($request);

        if ($mapRoutePathForm->isValid()) {
            $startPoint = $mapRoutePathForm->get('startPoint')->getData();
            $endPoint = $mapRoutePathForm->get('endPoint')->getData();
            $registrationHandler = $this->get('findness.mapRoute.registration');
            $response = $registrationHandler->updatePath($mapRoutePath,
                $startPoint,
                $endPoint);
            return new MapRoutePathResponse($response);
        }

        return $mapRoutePathForm->getErrors();
    }

    /**
     * Delete MapRoute Path
     *
     * @param MapRoute $mapRoute
     * @param MapRoutePath $mapRoutePath
     * @return boolean
     * @throws HttpException
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @FOSRestBundleAnnotations\Route("/map-routes/{mapRoute}/path/{mapRoutePath}")
     *
     * @ApiDoc(
     *  section="MapRoute",
     *  description="Delete MapRoute",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Returned on not found map route",
     *         500="Returned on not found map route path"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function deletePathAction(MapRoute $mapRoute = null, MapRoutePath $mapRoutePath = null)
    {
        if (!$mapRoute) {
            throw new HttpException(500, 'Map Route not found');
        }

        if (!$mapRoutePath) {
            throw new HttpException(500, 'Map Route Path not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($mapRoutePath);
        $em->flush();

        return true;
    }
}
