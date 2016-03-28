<?php

namespace spec\Customer\Registration;

use Customer\Customer\Customer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegistrationHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Customer\Registration\RegistrationHandler');
    }

    public function it_should_register_a_customer()
    {
        $id = uniqid();
        $customer = new Customer($id);
        $username = 'x@x.x';
        $password = 'xyz';
        $firstName = 'x';
        $lastName = 'y z';
        $salt = 'zxy';
        $customer = $this->register($customer, $username,
            $firstName,
            $lastName,
            $salt,
            $password);
        $customer->getUsername()->shouldBe($username);
        $customer->getEmail()->shouldBe($username);
        $customer->getRoles()->shouldHaveCount(1);
        $customer->getRoles()[0]->shouldBe('ROLE_USER');
        $customer->isEnabled()->shouldBe(true);
        $customer->getFirstName()->shouldBe($firstName);
        $customer->getLastName()->shouldBe($lastName);
        $customer->getSalt()->shouldBe($salt);
        $customer->getPassword()->shouldBe($password);
    }
}
