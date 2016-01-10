<?php

namespace spec\Seta\PenaltyBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;

class PenaltyRepositorySpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        ClassMetadata $metadata
    )
    {
        $metadata->beConstructedWith([Penalty::class]);

        $this->beConstructedWith($manager, $metadata);

    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Repository\PenaltyRepository');
    }

    function it_should_extend_from_repository_class()
    {
        $this->shouldHaveType('Seta\ResourceBundle\Doctrine\ORM\EntityRepository');
    }

    function it_create_new_without_rental(
        User $user
    )
    {
        $end = new \DateTime();
        $penalty = $this->createNew();
        $penalty->setComment("cause");
        $penalty->setEndAt($end);
        $penalty->setUser($user);

        $this->createFromData($user, $end, "cause")->shouldBeLike($penalty);
    }

    function it_create_new_with_rental(
        Rental $rental,
        User $user
    )
    {
        $end = new \DateTime();
        $penalty = $this->createNew();
        $penalty->setComment("cause");
        $penalty->setEndAt($end);
        $penalty->setUser($user);
        $penalty->setRental($rental);

        $this->createFromData($user, $end, "cause", $rental)->shouldBeLike($penalty);
    }

}
