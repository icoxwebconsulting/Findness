<?php

namespace spec\Finance\Finance;

use PhpSpec\ObjectBehavior;

class PaypalOperatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Finance\Finance\PaypalOperator');
    }

    public function it_should_get_id()
    {
        $this->getId()->shouldBe(2);
    }
}
