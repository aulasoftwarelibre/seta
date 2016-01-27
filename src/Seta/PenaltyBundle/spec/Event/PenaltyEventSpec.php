<?php

namespace spec\Seta\PenaltyBundle\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\PenaltyBundle\Entity\Penalty;
use Symfony\Component\EventDispatcher\Event;

class PenaltyEventSpec extends ObjectBehavior
{
    function let(Penalty $penalty)
    {
        $this->beConstructedWith($penalty);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Event\PenaltyEvent');
    }

    function it_is_an_event()
    {
        $this->shouldHaveType(Event::class);
    }
    
    function it_returns_penalty(Penalty $penalty)
    {
        $this->getPenalty()->shouldBe($penalty);
    }
}
