<?php

namespace StaticList\StaticList;

use Company\Company\CompanyInterface;
use Customer\Customer\CustomerInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class StaticList
 *
 * @package StaticList\StaticList
 */
class StaticList implements StaticListInterface
{
    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var ArrayCollection
     */
    protected $companies;

    /**
     * StaticList constructor.
     *
     * @param CustomerInterface $customer
     * @param string $name
     */
    public function __construct(CustomerInterface $customer, $name, $id = null)
    {
        $this->customer = $customer;
        $this->name = $name;
        $this->id = $id;

        if (!$this->id) {
            $this->id = uniqid();
        }

        $this->companies = new ArrayCollection();
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
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @inheritDoc
     */
    public function setCompanies(array $companies)
    {
        foreach ($companies as $company) {
            if (!($company instanceof CompanyInterface)) {
                throw new \Exception('Item not a company.');
            }
            $this->companies->add($company);
        }
    }

    /**
     * @inheritDoc
     */
    public function addCompany(CompanyInterface $company)
    {
        $this->companies->add($company);
    }

    /**
     * @inheritDoc
     */
    public function removeCompany(CompanyInterface $company)
    {
        $this->companies->removeElement($company);
    }
}