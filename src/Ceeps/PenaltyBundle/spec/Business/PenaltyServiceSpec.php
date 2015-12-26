<?php

namespace spec\Ceeps\PenaltyBundle\Business;

use Ceeps\PenaltyBundle\Entity\Penalty;
use Ceeps\PenaltyBundle\Repository\PenaltyRepository;
use Ceeps\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PenaltyServiceSpec extends ObjectBehavior
{
    function let(EntityManager $manager, PenaltyRepository $penaltyRepository)
    {
        $this->beConstructedWith($manager, $penaltyRepository);
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
        $penaltyRepository->createNew()->willReturn($penalty);

        $start = new \DateTime('now');
        $end = new \DateTime('+7 days');
        $comment = "Test";

        $penalty->setStartAt($start)->shouldBeCalled();
        $penalty->setEndAt($end)->shouldBeCalled();
        $penalty->setComment($comment)->shouldBeCalled();
        $penalty->setUser($user)->shouldBeCalled();

        $user->setIsPenalized(true)->shouldBeCalled();

        $manager->persist($penalty)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->penalizeUser($user, $start, $end, $comment);
    }
}
