<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Search;
use AppBundle\Form\SearchType;
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
            ->getOrderedByCreatedDesc($this->getUser());

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

    /**
     * Update search name
     *
     * @param Request $request
     * @param Search|null $search
     * @return array
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Search",
     *  description="Update search name",
     *  input="AppBundle\Form\SearchType",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Returned when search not found",
     *         500="Returned when current customer is not owner of search",
     *         500="Returned when search name not unique"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function putAction(Request $request, Search $search = null)
    {
        if (!$search) {
            throw new HttpException(500, 'Search not found');
        }

        $searchForm = $this->createForm(new SearchType(), null, array('method' => 'PUT'));

        $searchForm->handleRequest($request);

        if ($searchForm->isValid()) {
            $name = $searchForm->get('name')->getData();
            $registrationHandler = $this->get('findness.search');
            $updated = $registrationHandler->update($this->getUser(), $search, $name);
            return [
                "updated" => $updated
            ];
        }

        return $searchForm->getErrors();
    }

    /**
     * Delete search
     *
     * @param Search|null $search
     * @return array
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Search",
     *  description="Delete search",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Search not found."
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function deleteAction(Search $search = null)
    {
        if (!$search) {
            throw new HttpException(500, 'Search not found');
        }

        $registrationHandler = $this->get('findness.search');
        $deleted = $registrationHandler->delete($this->getUser(), $search);
        return [
            "deleted" => $deleted
        ];
    }
}
