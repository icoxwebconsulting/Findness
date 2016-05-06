<?php

namespace spec\Company\Company;

use Company\Company\Company;
use Customer\Customer\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CustomerViewCompanySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Company\Company\CustomerViewCompany');
    }

    public function it_should_set_and_get_customer()
    {
        $customer = new Customer();
        $this->setCustomer($customer);
        $this->getCustomer()->shouldBe($customer);
    }

    public function it_should_set_and_get_company()
    {
        $company = new Company();
        $this->setCompany($company);
        $this->getCompany()->shouldBe($company);
    }
}
