<?php

namespace spec\Finance\Finance;

use PhpSpec\ObjectBehavior;

class StripeOperatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Finance\Finance\StripeOperator');
    }

    public function it_should_get_id()
    {
        $this->getId()->shouldBe(3);
    }
}
