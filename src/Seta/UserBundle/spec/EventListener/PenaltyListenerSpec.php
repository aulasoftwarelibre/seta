<?php

namespace spec\Seta\UserBundle\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\PenaltyBundle\Event\PenaltyEvent;
use Seta\PenaltyBundle\PenaltyEvents;
use Seta\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PenaltyListenerSpec extends ObjectBehavior
{
    function let(
        ObjectManager $manager,
        Penalty $penalty,
        PenaltyEvent $event,
        User $user
    )
    {
        $this->beConstructedWith($manager);
        
        $event->getPenalty()->willReturn($penalty);
        $penalty->getUser()->willReturn($user);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\UserBundle\EventListener\PenaltyListener');
    }

    function it_has_event_suscriber_interface()
    {
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function its_subscribed_to_penalty_created_event()
    {
        $this->getSubscribedEvents()
            ->shouldHaveKeyWithValue(PenaltyEvents::PENALTY_CREATED, 'createdPenalty')
        ;
    }

    function its_subscribed_to_penalty_closed_event()
    {
        $this->getSubscribedEvents()
            ->shouldHaveKeyWithValue(PenaltyEvents::PENALTY_CLOSED, 'closedPenalty')
        ;
    }

    function it_sets_penalized_flag(
        ObjectManager $manager,
        PenaltyEvent $event,
        User $user
    )
    {
        $user->setIsPenalized(true)->shouldBeCalled();
        $manager->persist($user)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->createdPenalty($event);
    }

    function it_unsets_penalized_flag(
        ArrayCollection $collection,
        ArrayCollection $empty,
        ObjectManager $manager,
        PenaltyEvent $event,
        User $user
    )
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('status', Penalty::ACTIVE));

        $user->getPenalties()->shouldBeCalled()->willReturn($collection);
        $collection->matching($criteria)->shouldBeCalled()->willReturn($empty);
        $empty->count()->shouldBeCalled()->willReturn(0);

        $user->setIsPenalized(false)->shouldBeCalled();
        $manager->persist($user)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->closedPenalty($event);
    }

    function it_checks_if_have_pending_penalties(
        ArrayCollection $collection,
        ArrayCollection $empty,
        ObjectManager $manager,
        PenaltyEvent $event,
        User $user
    )
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('status', Penalty::ACTIVE));

        $user->getPenalties()->shouldBeCalled()->willReturn($collection);
        $collection->matching($criteria)->shouldBeCalled()->willReturn($empty);
        $empty->count()->shouldBeCalled()->willReturn(1);

        $this->closedPenalty($event);
    }
}
