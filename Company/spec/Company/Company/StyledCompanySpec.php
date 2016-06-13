<?php

namespace spec\Company\Company;

use Company\Company\Company;
use Customer\Customer\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StyledCompanySpec extends ObjectBehavior
{
    public function it_construct_with_uniqid()
    {
        $company = new Company();
        $customer = new Customer();

        $this->beConstructedWith($company, $customer);
        $this->getCompany()->shouldBe($company);
        $this->getCustomer()->shouldBe($customer);
    }

    public function it_should_set_and_get_company()
    {
        $company = new Company();
        $customer = new Customer();

        $this->beConstructedWith($company, $customer);
        $this->getCompany()->shouldBe($company);
        $this->getCustomer()->shouldBe($customer);

        $company = new Company();
        $this->setCompany($company);
        $this->getCompany()->shouldBe($company);
    }

    public function it_should_set_and_get_customer()
    {
        $company = new Company();
        $customer = new Customer();

        $this->beConstructedWith($company, $customer);
        $this->getCompany()->shouldBe($company);
        $this->getCustomer()->shouldBe($customer);

        $customer = new Customer();
        $this->setCustomer($customer);
        $this->getCustomer()->shouldBe($customer);
    }

    public function it_should_set_and_get_style()
    {
        $company = new Company();
        $customer = new Customer();

        $this->beConstructedWith($company, $customer);
        $this->getCompany()->shouldBe($company);
        $this->getCustomer()->shouldBe($customer);

        $style = "xyz";
        $this->setStyle($style);
        $this->getStyle()->shouldBe($style);
    }
}
