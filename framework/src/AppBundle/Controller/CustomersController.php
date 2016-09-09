<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerChangePasswordType;
use AppBundle\Form\CustomerConfirmationType;
use AppBundle\Form\CustomerNewPasswordType;
use AppBundle\Form\CustomerProfileType;
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
            if ($response) {
                return new CustomerResponse($response);
            } else {
                throw new HttpException(409, "El correo ya se encuentra registrado.");
            }
        }

        return $customerForm->getErrors();
    }

    /**
     * Confirm customer
     *
     * @param Customer $customer
     * @param Request $request
     * @return array\Symfony\Component\Form\FormErrorIterator
     *
     * @FOSRestBundleAnnotations\Route("/customers/{username}/confirm")
     * @ParamConverter("customer", options={"mapping": {"username": "username"}})
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Confirm Customer",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="username | email"
     *      }
     *  },
     *  input="AppBundle\Form\CustomerConfirmationType",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function postConfirmAction(Customer $customer = null, Request $request)
    {
        if (!$customer) {
            throw new HttpException(500, 'No se ha encontrado el cliente.');
        }

        $customerConfirmationForm = $this->createForm(new CustomerConfirmationType());

        $customerConfirmationForm->handleRequest($request);

        if ($customerConfirmationForm->isValid()) {
            $registrationHandler = $this->get('findness.customer.registration');
            $confirmed = $registrationHandler->confirm($customer, $customerConfirmationForm->get('token')->getData());
            return array(
                "confirmed" => $confirmed,
                "id" => $customer->getId()
            );
        }

        return $customerConfirmationForm->getErrors();
    }

    /**
     * Reset password Customer
     *
     * @param Customer $customer
     * @return array
     * @throws HttpException
     *
     * @FOSRestBundleAnnotations\Route("/customers/{username}/reset/password")
     * @ParamConverter("customer", options={"mapping": {"username": "username"}})
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Reset password customer",
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
     *         500="Returned on not found customer"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function putResetPasswordAction(Customer $customer = null)
    {
        if (!$customer) {
            throw new HttpException(500, 'No se ha encontrado el cliente.');
        }

        $registrationHandler = $this->get('findness.customer.registration');
        $response = $registrationHandler->resetPassword($customer);
        return [
            "status" => $response
        ];
    }

    /**
     * Update customer new password
     *
     * @param Request $request
     * @param Customer $customer
     * @return array
     * @FOSRestBundleAnnotations\Route("/customers/{username}/new/password")
     * @ParamConverter("customer", options={"mapping": {"username": "username"}})
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Update customer password",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="username | email"
     *      }
     *  },
     *  input="AppBundle\Form\CustomerNewPasswordType",
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
    public function putChangeNewPasswordAction(Request $request, Customer $customer = null)
    {
        $customerForm = $this->createForm(new CustomerNewPasswordType(), null, array('method' => 'PUT'));

        $customerForm->handleRequest($request);

        if ($customerForm->isValid()) {
            $code = $customerForm->get('code')->getData();
            $password = $customerForm->get('password')->getData();
            $salt = $customerForm->get('salt')->getData();
            $registrationHandler = $this->get('findness.customer.registration');
            $response = $registrationHandler->changeNewPassword($customer, $code, $password, $salt);
            return [
                "status" => $response
            ];
        }

        return $customerForm->getErrors();
    }

    /**
     * Update customer password
     *
     * @param Request $request
     * @return CustomerResponse\Symfony\Component\Form\FormErrorIterator
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Update customer password",
     *  input="AppBundle\Form\CustomerChangePasswordType",
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
    public function putChangePasswordAction(Request $request)
    {
        $customerForm = $this->createForm(new CustomerChangePasswordType(), null, array('method' => 'PUT'));

        $customerForm->handleRequest($request);

        if ($customerForm->isValid()) {
            $customer = $this->getUser();
            $salt = $customerForm->get('salt')->getData();
            $password = $customerForm->get('password')->getData();
            $registrationHandler = $this->get('findness.customer.registration');
            $response = $registrationHandler->changePassword($customer, $salt, $password);
            return new CustomerResponse($response);
        }

        return $customerForm->getErrors();
    }

    /**
     * Response with the customer that has {customer} for id
     *
     * @return CustomerResponse
     * @throws HttpException
     *
     * @FOSRestBundleAnnotations\Route("/customers/profile")
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Get a customer",
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
        return new CustomerResponse($this->getUser());
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
     *          "description"="username | email"
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
            throw new HttpException(500, 'No se ha encontrado el cliente.');
        }

        return [
            "salt" => $customer->getSalt()
        ];
    }

    /**
     * Resend Confirmation email
     *
     * @param Customer $customer
     * @param Request $request
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/customers/{username}/resend-confirmation-email")
     * @ParamConverter("customer", options={"mapping": {"username": "username"}})
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Resend Confirmation email",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="username | email"
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
    public function postResendConfirmationEmailAction(Customer $customer = null)
    {
        if (!$customer) {
            throw new HttpException(500, 'No se ha encontrado el cliente.');
        }

        $registrationHandler = $this->get('findness.customer.registration');
        $sent = $registrationHandler->resendConfirmationEmail($customer);
        return array(
            "sent" => $sent
        );
    }

    /**
     * Update customer profile
     *
     * @param Request $request
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/customers/update-profile")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Customer",
     *  description="Update Customer Profile",
     *  input="AppBundle\Form\CustomerProfileType",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function putCustomerProfileAction(Request $request)
    {
        $customerProfileForm = $this->createForm(new CustomerProfileType(), null, array('method' => 'PUT'));

        $customerProfileForm->handleRequest($request);

        if ($customerProfileForm->isValid()) {
            $firstName = $customerProfileForm->get('firstName')->getData();
            $lastName = $customerProfileForm->get('lastName')->getData();
            $registrationHandler = $this->get('findness.customer.registration');
            $updated = $registrationHandler->updateProfile($this->getUser(),
                $firstName,
                $lastName);

            return [
                "updated" => $updated
            ];
        }

        return $customerProfileForm->getErrors();
    }
}
