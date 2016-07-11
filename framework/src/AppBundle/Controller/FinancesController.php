<?php

namespace AppBundle\Controller;

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
class FinancesController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Create a new Transaction
     *
     * @param Request $request
     * @return TransactionResponse\Symfony\Component\Form\FormErrorIterator
     *
     * @FOSRestBundleAnnotations\Route("/transaction")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Finance",
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

    /**
     * Get Balance
     *
     * @return float
     *
     * @FOSRestBundleAnnotations\Route("/balance")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Finance",
     *  description="Get balance",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getBalanceAction()
    {
        $em = $this->getDoctrine()->getManager();
        return [
            "balance" => $em->getRepository("AppBundle:Balance")
                ->findOneByCustomer($this->getUser())
                ->getBalance()
        ];
    }

    /**
     * Get Transactions
     *
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/transaction")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Finance",
     *  description="Get transactions",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getTransactionsAction()
    {
        $em = $this->getDoctrine()->getManager();
        return [
            "transactions" => $em->getRepository("AppBundle:Transaction")
                ->findByCustomer($this->getUser())
        ];
    }
}
