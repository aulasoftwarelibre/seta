<?php

namespace spec\Seta\PenaltyBundle\Business;

use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Entity\FinancialPenalty;
use Seta\PenaltyBundle\Repository\FinancialPenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;

class FinancialPenaltyServiceSpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        FinancialPenaltyRepository $timePenaltyRepository,
        Locker $locker,
        Rental $rental,
        User $user
    )
    {
        $days_before_penalty = 8;
        $this->beConstructedWith($manager, $timePenaltyRepository, $days_before_penalty);

        $locker->getCode()->willReturn("100");

        $rental->getUser()->willReturn($user);
        $rental->getLocker()->willReturn($locker);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Business\FinancialPenaltyService');
    }

    function it_can_add_a_penalty(
        User $user,
        FinancialPenalty $penalty,
        EntityManager $manager,
        FinancialPenaltyRepository $timePenaltyRepository
    )
    {
        $amount = 2.5;
        $comment = "Test";

        $user->setIsPenalized(true)->shouldBeCalled();

        $timePenaltyRepository->createNew()->shouldBeCalled()->willReturn($penalty);
        $penalty->setUser($user)->shouldBeCalled();
        $penalty->setAmmount($amount)->shouldBeCalled();
        $penalty->setComment($comment)->shouldBeCalled();

        $manager->persist($user)->shouldBeCalled();
        $manager->persist($penalty)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->penalizeUser($user, $amount, $comment);
    }

    function it_can_add_a_penalty_from_a_rent(
        EntityManager $manager,
        Locker $locker,
        FinancialPenalty $penalty,
        FinancialPenaltyRepository $timePenaltyRepository,
        Rental $rental,
        User $user
    )
    {
        $amount = 2.5;

        $rental->getLocker()->shouldBeCalled();
        $locker->getCode()->shouldBeCalled();
        $comment = "Penalización automática por retraso al entregar la taquilla 100";

        $rental->getUser()->shouldBeCalled();
        $user->setIsPenalized(true)->shouldBeCalled();

        $timePenaltyRepository->createNew()->shouldBeCalled()->willReturn($penalty);
        $penalty->setUser($user)->shouldBeCalled();
        $penalty->setAmmount($amount)->shouldBeCalled();
        $penalty->setComment($comment)->shouldBeCalled();
        $penalty->setRental($rental)->shouldBeCalled();

        $manager->persist($user)->shouldBeCalled();
        $manager->persist($penalty)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->penalizeRental($rental, $amount);
    }

}
