<?php

namespace spec\Ceeps\PenaltyBundle\Business;

use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\PenaltyBundle\Entity\Penalty;
use Ceeps\PenaltyBundle\Repository\PenaltyRepository;
use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
        $this->beConstructedWith($manager, $penaltyRepository);

        $locker->getCode()->willReturn("100");
        
        $rental->getUser()->willReturn($user);
        $rental->getLocker()->willReturn($locker);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ceeps\PenaltyBundle\Business\PenaltyService');
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
        $rental->getEndAt()->willReturn(new \DateTime('-1 days 23:59:59'));
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
        $end = new \DateTime('-1 days 23:59:59');
        $rental->getEndAt()->shouldBeCalled()->willReturn($end);

        $this->calculatePenalty($rental)->shouldBeLike(new \DateTime('7 days midnight'));
    }
}
