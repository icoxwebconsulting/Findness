<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transaction;
use AppBundle\Form\TransactionType;
use AppBundle\ResponseObjects\Transaction as TransactionResponse;
use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TransactionController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class TransactionController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Create a new Transaction
     *
     * @param Request $request
     * @return TransactionResponse\Symfony\Component\Form\FormErrorIterator
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Transactions",
     *  description="Create a new Transaction",
     *  input="AppBundle\Form\TransactionType",
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
        $mapRouteForm = $this->createForm(new TransactionType());

        $mapRouteForm->handleRequest($request);

        if ($mapRouteForm->isValid()) {
            $customer = $this->getUser();
            $balance = $mapRouteForm->get('balance')->getData();
            $operator = $mapRouteForm->get('operator')->getData();
            $transactionId = $mapRouteForm->get('transactionId')->getData();
            $cardId = $mapRouteForm->get('cardId')->getData();
            $registrationHandler = $this->get('findness.transaction.registration');
            $response = $registrationHandler->register($customer,
                $balance,
                $operator,
                $transactionId,
                $cardId);
            return new TransactionResponse($response);
        }

        return $mapRouteForm->getErrors();
    }
}
