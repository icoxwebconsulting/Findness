<?php

namespace Company\Company;

use Customer\Customer\CustomerInterface;

/**
 * Class StyledCompany
 *
 * @package Company\Company
 */
class StyledCompany implements StyledCompanyInterface
{
    /**
     * @var CompanyInterface
     */
    protected $company;

    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @var mixed
     */
    protected $style;

    /**
     * StyledCompany constructor.
     *
     * @param CompanyInterface $company
     * @param CustomerInterface $customer
     */
    public function __construct(CompanyInterface $company, CustomerInterface $customer)
    {
        $this->company = $company;
        $this->customer = $customer;
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
     * @inheritDoc
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @inheritDoc
     */
    public function getStyle()
    {
        return $this->style;
    }
}