<?php

namespace spec\Seta\PenaltyBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use App\Services\ClosePenaltyInterface;
use App\Entity\Penalty;
use App\Event\PenaltyEvent;
use App\Exception\PenaltyDoneException;
use App\Events\PenaltyEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClosePenaltyServiceSpec extends ObjectBehavior
{
    public function let(
        ObjectManager $manager,
        Penalty $penalty,
        EventDispatcherInterface $dispatcher
    ) {
        $this->beConstructedWith($manager, $dispatcher);

        $penalty->getStatus()->willReturn(Penalty::ACTIVE);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Services\ClosePenaltyService');
    }

    public function it_has_close_penalty_inteface()
    {
        $this->shouldHaveType(ClosePenaltyInterface::class);
    }

    public function it_closes_penalty(
        ObjectManager $manager,
        Penalty $penalty,
        EventDispatcherInterface $dispatcher
    ) {
        $penalty->close()->shouldBeCalled();
        $manager->persist($penalty)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $event = new PenaltyEvent($penalty->getWrappedObject());
        $dispatcher->dispatch(PenaltyEvents::PENALTY_CLOSED, $event)->shouldBeCalled();

        $this->closePenalty($penalty);
    }

    public function it_cannot_closes_penalty_twice(
        Penalty $penalty
    ) {
        $penalty->getStatus()->willReturn(Penalty::DONE);

        $this->shouldThrow(PenaltyDoneException::class)->duringClosePenalty($penalty);
    }
}
