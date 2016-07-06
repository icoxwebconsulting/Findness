<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class SearchesController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class SearchesController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get searches
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Search",
     *  description="Get search",
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
        $searchesEntity = $this->getDoctrine()
            ->getRepository('AppBundle:Search')
            ->findBy(array(
                'customer' => $this->getUser()->getId()
            ));

        $searches = array();
        foreach ($searchesEntity as $entity) {
            $searches[] = array(
                'id' => $entity->getId(),
                'name' => $entity->getName(),
                'filters' => $entity->getFilters()
            );
        }

        return array(
            'searches' => $searches
        );
    }
}
