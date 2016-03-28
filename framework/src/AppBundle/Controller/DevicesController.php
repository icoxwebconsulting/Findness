<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
use AppBundle\Form\DeviceType;
use AppBundle\ResponseObjects\DeviceRegistration;
use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class DevicesController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class DevicesController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Register a new device
     *
     * @param Request $request
     * @return DeviceRegistration|\Symfony\Component\Form\FormErrorIterator
     * @throws HttpException
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Device",
     *  description="Register a new Device",
     *  input="AppBundle\Form\DeviceType",
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
    public function postAction(Request $request)
    {
        $device = new Device($request->request->get('token'));
        $deviceForm = $this->createForm(new DeviceType(), $device);

        $deviceForm->handleRequest($request);

        if ($deviceForm->isValid()) {
            $registrationHandler = $this->get('findness.device.registration');
            $response = $registrationHandler->register($device, $this->getUser());

            if ($response) {
                return new DeviceRegistration($response['customer'], $response['device']);
            }

            throw new HttpException(500, 'Customer has this device registered');
        }

        return $deviceForm->getErrors();
    }

    /**
     * Un-Register a device
     *
     * @param Device $device
     * @return boolean
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Device",
     *  description="Un-Register device",
     *  requirements={
     *      {
     *          "name"="device",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="device id"
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
    public function deleteAction(Device $device)
    {
        $registrationHandler = $this->get('findness.device.registration');
        return $registrationHandler->unRegister($device);
    }
}