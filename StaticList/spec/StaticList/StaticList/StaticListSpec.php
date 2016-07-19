<?php

namespace spec\StaticList\StaticList;

use Company\Company\Company;
use Customer\Customer\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StaticListSpec extends ObjectBehavior
{
    public function it_construct_without_id()
    {
        $customer = new Customer();
        $name = uniqid();
        $this->beConstructedWith($customer, $name);
        $this->getCustomer()->shouldBe($customer);
        $this->getName()->shouldBe($name);
        $this->shouldHaveType('StaticList\StaticList\StaticList');
    }

    public function it_construct_with_id()
    {
        $customer = new Customer();
        $name = uniqid();
        $id = uniqid();
        $this->beConstructedWith($customer, $name, $id);
        $this->getCustomer()->shouldBe($customer);
        $this->getName()->shouldBe($name);
        $this->getId()->shouldBe($id);
        $this->shouldHaveType('StaticList\StaticList\StaticList');
    }

    public function it_should_set_customer()
    {
        $customer = new Customer();
        $name = uniqid();
        $this->beConstructedWith($customer, $name);
        $this->getCustomer()->shouldBe($customer);
        $customer2 = new Customer();
        $this->setCustomer($customer2);
        $this->getCustomer()->shouldBe($customer2);
    }

    public function it_should_set_get_name()
    {
        $customer = new Customer();
        $name = uniqid();
        $this->beConstructedWith($customer, $name);
        $this->getCustomer()->shouldBe($customer);
        $name = uniqid();
        $this->setName($name);
        $this->getName()->shouldBe($name);
    }

    public function it_should_set_get_companies()
    {
        $customer = new Customer();
        $name = uniqid();
        $this->beConstructedWith($customer, $name);
        $this->getCustomer()->shouldBe($customer);
        $companies = [
            new Company(),
            new Company(),
            new Company(),
            new Company()
        ];
        $this->setCompanies($companies);
        $acCompanies = new ArrayCollection();
        foreach ($companies as $company) {
            $acCompanies->add($company);
        }
        $this->getCompanies()->shouldBeLike($acCompanies);
    }

    public function it_should_add_company()
    {
        $customer = new Customer();
        $name = uniqid();
        $this->beConstructedWith($customer, $name);
        $this->getCustomer()->shouldBe($customer);
        $companies = [
            new Company(),
            new Company(),
            new Company(),
            new Company()
        ];
        $this->setCompanies($companies);
        $acCompanies = new ArrayCollection();
        foreach ($companies as $company) {
            $acCompanies->add($company);
        }
        $this->getCompanies()->shouldBeLike($acCompanies);
        $newCompany = new Company();
        $acCompanies->add($newCompany);
        $this->addCompany($newCompany);
        $this->getCompanies()->shouldBeLike($acCompanies);
    }

    public function it_should_remove_company()
    {
        $customer = new Customer();
        $name = uniqid();
        $this->beConstructedWith($customer, $name);
        $this->getCustomer()->shouldBe($customer);
        $company = new Company();
        $companies = [
            new Company(),
            $company,
            new Company(),
            new Company()
        ];
        $this->setCompanies($companies);
        $acCompanies = new ArrayCollection();
        foreach ($companies as $company) {
            $acCompanies->add($company);
        }
        $this->getCompanies()->shouldBeLike($acCompanies);
        $acCompanies->removeElement($company);
        $this->removeCompany($company);
        $this->getCompanies()->shouldBeLike($acCompanies);
    }
}
