<?php

namespace Company\Company;

use Customer\Customer\CustomerDependantInterface;
use Customer\Customer\CustomerInterface;

/**
 * Class CustomerViewCompany
 *
 * @package Company\Company
 */
class CustomerViewCompany implements CustomerViewCompanyInterface,
    CustomerDependantInterface,
    CompanyDependantInterface
{

    /**
     * @var CustomerInterface
     */
    protected $customer;


    /**
     * @var CompanyInterface
     */
    protected $company;

    /**
     * @inheritdoc
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @inheritdoc
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @inheritdoc
     */
    public function setCompany(CompanyInterface $company)
    {
        $this->company = $company;
    }

    /**
     * @inheritdoc
     */
    public function getCompany()
    {
        return $this->company;
    }
}