<?php

namespace spec\Seta\PenaltyBundle\Business;

use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Entity\TimePenalty;
use Seta\PenaltyBundle\Event\PenaltyEvent;
use Seta\PenaltyBundle\PenaltyEvents;
use Seta\PenaltyBundle\Repository\TimePenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Exception\NotExpiredRentalException;
use Seta\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TimePenaltyServiceSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $dispatcher,
        ObjectManager $manager,
        TimePenaltyRepository $timePenaltyRepository,
        Locker $locker,
        Rental $rental,
        User $user
    ) {
        $days_before_penalty = 8;
        $this->beConstructedWith($manager, $timePenaltyRepository, $dispatcher, $days_before_penalty);

        $locker->getCode()->willReturn('100');

        $rental->getUser()->willReturn($user);
        $rental->getLocker()->willReturn($locker);
        $rental->getDaysLate()->willReturn(2);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Business\TimePenaltyService');
    }

    public function it_can_add_a_penalty_from_a_rent(
        EventDispatcherInterface $dispatcher,
        ObjectManager $manager,
        Locker $locker,
        TimePenalty $penalty,
        TimePenaltyRepository $timePenaltyRepository,
        Rental $rental,
        User $user
    ) {
        $end = $this->calculatePenalty($rental);

        $rental->getLocker()->shouldBeCalled();
        $locker->getCode()->shouldBeCalled();
        $comment = 'Bloqueo automático por retraso al entregar la taquilla 100';

        $rental->getUser()->shouldBeCalled();

        $timePenaltyRepository->createNew()->shouldBeCalled()->willReturn($penalty);
        $penalty->setUser($user)->shouldBeCalled();
        $penalty->setEndAt($end)->shouldBeCalled();
        $penalty->setComment($comment)->shouldBeCalled();
        $penalty->setRental($rental)->shouldBeCalled();

        $manager->persist($penalty)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $dispatcher->dispatch(PenaltyEvents::PENALTY_CREATED, Argument::type(PenaltyEvent::class))->shouldBeCalled();

        $this->penalizeRental($rental);
    }

    public function it_can_calculate_penalty(
        Rental $rental
    ) {
        $rental->getDaysLate()->shouldBeCalled();

        $this->calculatePenalty($rental)->shouldBeLike(new \DateTime('14 days midnight'));
    }

    public function it_can_calculate_season_penalty($rental)
    {
        $rental->getDaysLate()->shouldBeCalled()->willReturn(8);

        $this->calculatePenalty($rental)->shouldBeLike(TimePenalty::getEndSeasonPenalty());
    }

    public function it_cannot_calculate_not_expired_rental($rental)
    {
        $rental->getDaysLate()->shouldBeCalled()->willReturn(0);

        $this->shouldThrow(NotExpiredRentalException::class)->duringCalculatePenalty($rental);
    }
}
