<?php

namespace StaticList\StaticList;

use Company\Company\CompanyInterface;
use Customer\Customer\CustomerDependantInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface StaticList
 *
 * @package StaticList\StaticList
 */
interface StaticListInterface extends CustomerDependantInterface
{
    /**
     * Get name
     *
     * @return string
     */
    public function getId();

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get companies
     *
     * @return ArrayCollection
     */
    public function getCompanies();

    /**
     * Set companies
     *
     * @param array $companies
     */
    public function setCompanies(array $companies);

    /**
     * Add company
     *
     * @param CompanyInterface $company
     */
    public function addCompany(CompanyInterface $company);

    /**
     * Remove company
     *
     * @param CompanyInterface $company
     */
    public function removeCompany(CompanyInterface $company);
}