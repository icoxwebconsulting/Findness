<?php

namespace AppBundle\Controller;

use AppBundle\Form\StaticListType;
use AppBundle\ResponseObjects\StaticList;
use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class StaticListController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class StaticListsController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Create static list
     *
     * @param Request $request
     * @return StaticListResponse\Symfony\Component\Form\FormErrorIterator
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="StaticList",
     *  description="Create static list",
     *  input="AppBundle\Form\StaticListType",
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
        $staticListForm = $this->createForm(new StaticListType());

        $staticListForm->handleRequest($request);

        if ($staticListForm->isValid()) {
            $name = $staticListForm->get('name')->getData();
            $companies = json_decode($staticListForm->get('companies')->getData());
            $registrationHandler = $this->get('findness.staticlist');
            $staticList = $registrationHandler->register($this->getUser(), $name, $companies);
            return new StaticList($staticList);
        }

        return $staticListForm->getErrors();
    }
}
