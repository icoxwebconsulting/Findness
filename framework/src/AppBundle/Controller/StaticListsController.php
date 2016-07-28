<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Form\StaticListType;
use AppBundle\ResponseObjects\StaticList as StaticListResponse;
use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     *         200="Returned when successful",
     *         500="Returned when list name not unique"
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
            return new StaticListResponse($staticList);
        }

        return $staticListForm->getErrors();
    }

    /**
     * Create static list
     *
     * @param string $staticList
     * @param Customer $customer
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/static/list/{staticList}/share/{username}")
     * @ParamConverter("customer", options={"mapping": {"username": "username"}})
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="StaticList",
     *  description="Share static list",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="username | email"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Customer not found.",
     *         500="Cant share with static list owner."
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function postShareAction($staticList, Customer $customer = null)
    {
        if (!$customer) {
            throw new HttpException(500, 'Customer not found.');
        }

        $registrationHandler = $this->get('findness.staticlist');
        $shared = $registrationHandler->share($staticList, $this->getUser(), $customer);
        return [
            "shared" => $shared
        ];
    }

    /**
     * Get static lists
     *
     * @return array
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="StaticList",
     *  description="Get static lists",
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
        $registrationHandler = $this->get('findness.staticlist');
        return $registrationHandler->get($this->getUser());
    }

    /**
     * Get static lists
     *
     * @param string $staticList
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/static/list/{staticList}")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="StaticList",
     *  description="Get static list",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Static list not found."
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getStaticListAction($staticList)
    {
        $registrationHandler = $this->get('findness.staticlist');
        return $registrationHandler->getStaticList($this->getUser(), $staticList);
    }

    /**
     * Delete static list
     *
     * @param string $staticList
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/static/list/{staticList}")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="StaticList",
     *  description="Delete static list",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Customer not owner."
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function deleteListAction($staticList)
    {
        $registrationHandler = $this->get('findness.staticlist');
        $deleted = $registrationHandler->deleteStaticList($this->getUser(), $staticList);
        return [
            "deleted" => $deleted
        ];
    }
}
