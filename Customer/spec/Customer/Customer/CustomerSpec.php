<?php

namespace spec\Customer\Customer;

use Customer\Customer\CustomerProfile;
use Customer\Registration\RegistrationAttempt;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CustomerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Customer\Customer\Customer');
    }

    public function it_is_fosuserbundle_entity()
    {
        $this->shouldHaveType('FOS\UserBundle\Model\User');
    }

    public function it_implement_symfony_advanced_user_interface()
    {
        $this->shouldHaveType('Symfony\Component\Security\Core\User\AdvancedUserInterface');
    }

    public function it_construct_with_uniqid()
    {
        $id = uniqid();
        $this->beConstructedWith($id);
        $this->getId()->shouldBe($id);
    }

    public function it_should_set_and_get_first_name()
    {
        $firstName = "Yasmany";
        $this->setFirstName($firstName);
        $this->getFirstName()->shouldBe($firstName);
    }

    public function it_should_set_and_get_last_name()
    {
        $lastName = "Cubela Medina";
        $this->setLastName($lastName);
        $this->getLastName()->shouldBe($lastName);
    }

    public function it_should_get_full_name()
    {
        $firstName = "Yasmany";
        $lastName = "Cubela Medina";
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->getFullName()->shouldBe(sprintf("%s %s",
            $firstName,
            $lastName));
    }

    public function it_should_set_salt()
    {
        $salt = "a0sd80a8sd0asd";
        $this->setSalt($salt);
        $this->getSalt()->shouldBe($salt);
    }
}
