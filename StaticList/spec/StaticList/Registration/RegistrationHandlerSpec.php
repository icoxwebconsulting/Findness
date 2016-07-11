<?php

namespace spec\StaticList\Registration;

use Company\Company\Company;
use Customer\Customer\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegistrationHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('StaticList\Registration\RegistrationHandler');
    }

    public function it_should_register_an_static_list()
    {
        $customer = new Customer();
        $name = uniqid();
        $companies = [
            new Company(),
            new Company(),
            new Company(),
            new Company()
        ];
        $staticList = $this->register($customer, $name, $companies);
        $staticList->getCustomer()->shouldBe($customer);
        $staticList->getName()->shouldBe($name);
        $acCompanies = new ArrayCollection();
        foreach ($companies as $company) {
            $acCompanies->add($company);
        }
        $staticList->getCompanies()->shouldBeLike($acCompanies);
    }
}
