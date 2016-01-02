<?php

namespace spec\Seta\PenaltyBundle\Business;

use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\PenaltyBundle\Repository\PenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Exception\NotExpiredRentalException;
use Seta\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PenaltyServiceSpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        PenaltyRepository $penaltyRepository,
        Locker $locker,
        Rental $rental,
        User $user
    )
    {
        $days_before_penalty = 8;
        $this->beConstructedWith($manager, $penaltyRepository, $days_before_penalty);

        $locker->getCode()->willReturn("100");
        
        $rental->getUser()->willReturn($user);
        $rental->getLocker()->willReturn($locker);
        $rental->getDaysLate()->willReturn(2);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Business\PenaltyService');
    }

    function it_can_add_a_penalty(
        User $user,
        Penalty $penalty,
        EntityManager $manager,
        PenaltyRepository $penaltyRepository
    )
    {
        $end = new \DateTime('+7 days');
        $comment = "Test";

        $user->setIsPenalized(true)->shouldBeCalled();

        $penaltyRepository->createFromData($user, $end, $comment)->willReturn($penalty);

        $manager->persist($user)->shouldBeCalled();
        $manager->persist($penalty)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->penalizeUser($user, $end, $comment);
    }

    function it_can_add_a_penalty_from_a_rent(
        EntityManager $manager,
        Locker $locker,
        Penalty $penalty,
        PenaltyRepository $penaltyRepository,
        Rental $rental,
        User $user
    )
    {
        $end = $this->calculatePenalty($rental);

        $rental->getLocker()->shouldBeCalled();
        $locker->getCode()->shouldBeCalled();
        $comment = "Bloqueo automático por retraso al entregar la taquilla 100";

        $rental->getUser()->shouldBeCalled();
        $user->setIsPenalized(true)->shouldBeCalled();

        $penaltyRepository->createFromData($user, $end, $comment, $rental)->shouldBeCalled()->willReturn($penalty);

        $manager->persist($user)->shouldBeCalled();
        $manager->persist($penalty)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->penalizeRental($rental);
    }

    function it_can_calculate_penalty(
        Rental $rental
    )
    {
        $rental->getDaysLate()->shouldBeCalled();

        $this->calculatePenalty($rental)->shouldBeLike(new \DateTime('14 days midnight'));
    }

    function it_can_calculate_season_penalty($rental)
    {
        $rental->getDaysLate()->shouldBeCalled()->willReturn(8);

        $this->calculatePenalty($rental)->shouldBeLike(Penalty::getEndSeasonPenalty());
    }

    function it_cannot_calculate_not_expired_rental($rental)
    {
        $rental->getDaysLate()->shouldBeCalled()->willReturn(0);

        $this->shouldThrow(NotExpiredRentalException::class)->duringCalculatePenalty($rental);
    }
}
