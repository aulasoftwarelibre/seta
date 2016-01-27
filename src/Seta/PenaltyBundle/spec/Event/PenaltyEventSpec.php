<?php

namespace spec\Seta\PenaltyBundle\Event;

use PhpSpec\ObjectBehavior;
use Seta\PenaltyBundle\Entity\Penalty;
use Symfony\Component\EventDispatcher\Event;

class PenaltyEventSpec extends ObjectBehavior
{
    public function let(Penalty $penalty)
    {
        $this->beConstructedWith($penalty);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Event\PenaltyEvent');
    }

    public function it_is_an_event()
    {
        $this->shouldHaveType(Event::class);
    }

    public function it_returns_penalty(Penalty $penalty)
    {
        $this->getPenalty()->shouldBe($penalty);
    }
}
