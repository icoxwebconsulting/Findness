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
        $customerForm = $this->createForm(new MapRouteType());

        $customerForm->handleRequest($request);

        if ($customerForm->isValid()) {
            $mapRoute = new MapRoute();
            $customer = $this->getUser();
            $name = $customerForm->get('name')->getData();
            $transport = $customerForm->get('transport')->getData();
            $registrationHandler = $this->get('findness.mapRoute.registration');
            $response = $registrationHandler->register($mapRoute,
                $name,
                $transport,
                $customer);
            return new MapRouteResponse($response);
        }

        return $customerForm->getErrors();
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
    public function getAction()
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
}
