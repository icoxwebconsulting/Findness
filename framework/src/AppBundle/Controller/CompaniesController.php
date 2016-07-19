<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Form\CompanyStyleType;
use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CompaniesController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class CompaniesController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get Company
     *
     * @param Company $company
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/companies/{company}")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Company",
     *  description="Get Company",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Company not found"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getAction(Company $company = null)
    {
        if (!$company) {
            throw new HttpException(500, 'Company not found.');
        }

        $style = $this->getDoctrine()
            ->getRepository('AppBundle:StyledCompany')
            ->findOneBy([
                "company" => $company->getId(),
                "customer" => $this->getUser()->getId()
            ]);

        return [
            "company" => $company,
            "style" => $style
        ];
    }

    /**
     * Update Company Style
     *
     * @param Company $company
     * @param Request $request
     * @return array|Symfony\Component\Form\FormErrorIterator
     *
     * @FOSRestBundleAnnotations\Route("/companies/{company}/style")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc(
     *  section="Company",
     *  description="Update Company Style",
     *  input="AppBundle\Form\CompanyStyleType",
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Company not found"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function putAction($company, Request $request)
    {
        $companyStyleForm = $this->createForm(new CompanyStyleType(), null, array('method' => 'PUT'));

        $companyStyleForm->handleRequest($request);

        if ($companyStyleForm->isValid()) {
            $style = $companyStyleForm->get('style')->getData();
            $registrationHandler = $this->get('findness.company');
            $updated = $registrationHandler->updateStyle($this->getUser(), $company, $style);
            return array(
                'updated' => $updated
            );
        }

        return $companyStyleForm->getErrors();
    }
}
