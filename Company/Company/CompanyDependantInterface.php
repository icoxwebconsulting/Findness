<?php

namespace Company\Company;

/**
 * Interface CompanyDependantInterface
 *
 * @package Company\Company
 */
interface CompanyDependantInterface
{
    /**
     * Set Company
     *
     * @param CompanyInterface $company
     */
    public function setCompany(CompanyInterface $company);

    /**
     * Get Company
     *
     * @return CompanyInterface
     */
    public function getCompany();
}