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
        Penalty $penalty
    )
    {
        $this->beConstructedWith($manager, $penaltyRepository);
        $penaltyRepository->createNew()->willReturn($penalty);
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

        $penalty->setEndAt($end)->shouldBeCalled();
        $penalty->setComment($comment)->shouldBeCalled();
        $penalty->setUser($user)->shouldBeCalled();

        $user->setIsPenalized(true)->shouldBeCalled();

        $manager->persist($penalty)->shouldBeCalled();
        $manager->persist($user)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->penalizeUser($user, $end, $comment);
    }

    function it_can_add_a_penalty_from_a_rent(
        Locker $locker,
        Rental $rental,
        User $user
    )
    {
        $rental->getUser()->shouldBeCalled()->willReturn($user);
        $rental->getLocker()->shouldBeCalled()->willReturn($locker);
        $rental->getEndAt()->shouldBeCalled()->willReturn(new \DateTime("-2 days"));
        $locker->getCode()->shouldBeCalled()->willReturn("100");

        $end = new \DateTime("+14 days 23:59:59");
        $comment = "Bloqueo automático por retraso al entregar la taquilla 100";

        $this->penalizeUser($user, $end, $comment)->shouldBe(null);

        $this->penalizeRental($rental);
    }
}
