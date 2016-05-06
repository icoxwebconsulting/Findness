<?php

namespace spec\Company\Company;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompanySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Company\Company\Company');
    }

    public function it_construct_with_uniqid()
    {
        $id = uniqid();
        $this->beConstructedWith($id);
        $this->getId()->shouldBe($id);
    }

    public function it_should_set_and_get_external_id()
    {
        $externalId = uniqid();
        $this->setExternalId($externalId);
        $this->getExternalId()->shouldBe($externalId);
    }
}
