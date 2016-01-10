<?php

namespace spec\Seta\RentalBundle\Business;

use Seta\LockerBundle\Entity\Locker;
use Seta\LockerBundle\Exception\NotRentedLockerException;
use Seta\PenaltyBundle\Business\PenaltyService;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Repository\RentalRepository;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReturnServiceSpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        PenaltyService $penaltyService,
        RentalRepository $rentalRepository,
        Locker $locker,
        Rental $rental
    )
    {
        $this->beConstructedWith($manager, $dispatcher, $penaltyService, $rentalRepository);

        $locker->getStatus()->willReturn(Locker::RENTED);

        $rentalRepository->getCurrentRental($locker)->willReturn($rental);
        $rental->getIsRenewable()->willReturn(true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Business\ReturnService');
    }

    function it_can_return_a_locker_before_due_date(
        Locker $locker,
        Rental $rental,
        EntityManager $manager
    )
    {
        $rental->getEndAt()->shouldBeCalled()->willReturn(new \DateTime('tomorrow'));
        $rental->setReturnAt(Argument::type(\DateTime::class))->shouldBeCalled();

        $locker->setOwner(null)->shouldBeCalled();
        $locker->setStatus(Locker::AVAILABLE)->shouldBeCalled();

        $manager->persist($rental)->shouldBeCalled();
        $manager->persist($locker)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->returnLocker($locker);
    }

    function it_cannot_return_a_not_rented_locker(
        Locker $locker
    )
    {
        $locker->getStatus()->shouldBeCalled()->willReturn(Locker::AVAILABLE);

        $this->shouldThrow(NotRentedLockerException::class)->duringReturnLocker($locker);
    }

    function it_can_return_a_locker_after_due_date(
        Locker $locker,
        Rental $rental,
        EntityManager $manager,
        PenaltyService $penaltyService
    )
    {
        $rental->getEndAt()->shouldBeCalled()->willReturn(new \DateTime('yesterday'));
        $rental->setReturnAt(Argument::type(\DateTime::class))->shouldBeCalled();

        $locker->setOwner(null)->shouldBeCalled();
        $locker->setStatus(Locker::AVAILABLE)->shouldBeCalled();

        $penaltyService->penalizeRental($rental)->shouldBeCalled();

        $manager->persist($rental)->shouldBeCalled();
        $manager->persist($locker)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->returnLocker($locker);
    }
}
