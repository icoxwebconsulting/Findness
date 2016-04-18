<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerType;
use AppBundle\ResponseObjects\Customer as CustomerResponse;
use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CustomersController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class CustomersController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Create a new customer
     *
     * @param Request $request
     * @return CustomerResponse\Symfony\Component\Form\FormErrorIterator
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Create a new Customer",
     *  input="AppBundle\Form\CustomerType",
     *  output="AppBundle\Entity\Customer",
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
        $customerForm = $this->createForm(new CustomerType());

        $customerForm->handleRequest($request);

        if ($customerForm->isValid()) {
            $customer = new Customer();
            $username = $customerForm->get('username')->getData();
            $firstName = $customerForm->get('firstName')->getData();
            $lastName = $customerForm->get('lastName')->getData();
            $salt = $customerForm->get('salt')->getData();
            $password = $customerForm->get('password')->getData();
            $registrationHandler = $this->get('findness.customer.registration');
            $response = $registrationHandler->register($customer,
                $username,
                $firstName,
                $lastName,
                $salt,
                $password);
            return new CustomerResponse($response);
        }

        return $customerForm->getErrors();
    }

    /**
     * Response with the customer that has {customer} for id
     *
     * @param Customer $customer
     * @return CustomerResponse
     * @throws HttpException
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Get a customer",
     *  requirements={
     *      {
     *          "name"="customer",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="customer id"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Returned on not found customer"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getAction(Customer $customer = null)
    {
        if (!$customer) {
            throw new HttpException(500, 'Customer not found');
        }

        return new CustomerResponse($customer);
    }

    /**
     * Response with the customer salt by {username}
     *
     * @param Customer $customer
     * @return CustomerResponse
     * @throws HttpException
     *
     * @FOSRestBundleAnnotations\Route("/customers/{username}/salt")
     * @ParamConverter("customer", options={"mapping": {"username": "username"}})
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Get a customer salt",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="customer id"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Returned on not found customer"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getSaltAction(Customer $customer = null)
    {
        if (!$customer) {
            throw new HttpException(500, 'Customer not found');
        }

        return $customer->getSalt();
    }
}
