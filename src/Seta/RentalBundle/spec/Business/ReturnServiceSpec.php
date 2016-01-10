<?php

namespace spec\Seta\RentalBundle\Business;

use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Business\PenaltyService;
use Seta\RentalBundle\Entity\Rental;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\Exception\FinishedRentalException;
use Seta\RentalBundle\RentalEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReturnServiceSpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        PenaltyService $penaltyService,
        Locker $locker,
        Rental $rental
    )
    {
        $this->beConstructedWith($manager, $dispatcher, $penaltyService);

        $rental->getIsRenewable()->willReturn(true);
        $rental->getLocker()->willReturn($locker);
        $rental->getReturnAt()->willReturn(null);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Business\ReturnService');
    }

    function it_can_return_a_locker_before_due_date(
        Locker $locker,
        Rental $rental,
        EntityManager $manager,
        EventDispatcherInterface $dispatcher
    )
    {
        $rental->getEndAt()->shouldBeCalled()->willReturn(new \DateTime('tomorrow'));
        $rental->setReturnAt(Argument::type(\DateTime::class))->shouldBeCalled();

        $locker->setOwner(null)->shouldBeCalled();
        $locker->setStatus(Locker::AVAILABLE)->shouldBeCalled();

        $manager->persist($rental)->shouldBeCalled();
        $manager->persist($locker)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $dispatcher->dispatch(RentalEvents::LOCKER_RETURNED, Argument::type(RentalEvent::class))->shouldBeCalled();

        $this->returnRental($rental);
    }

    function it_cannot_return_a_returned_rental(
        Rental $rental
    )
    {
        $rental->getReturnAt()->shouldBeCalled()->willReturn(new \DateTime());

        $this->shouldThrow(FinishedRentalException::class)->duringReturnRental($rental);
    }

    function it_can_return_a_locker_after_due_date(
        Locker $locker,
        Rental $rental,
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        PenaltyService $penaltyService
    )
    {
        $rental->getEndAt()->shouldBeCalled()->willReturn(new \DateTime('yesterday'));
        $rental->setReturnAt(Argument::type(\DateTime::class))->shouldBeCalled();

        $penaltyService->penalizeRental($rental)->shouldBeCalled();

        $locker->setOwner(null)->shouldBeCalled();
        $locker->setStatus(Locker::AVAILABLE)->shouldBeCalled();

        $manager->persist($rental)->shouldBeCalled();
        $manager->persist($locker)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $dispatcher->dispatch(RentalEvents::LOCKER_RETURNED, Argument::type(RentalEvent::class))->shouldBeCalled();

        $this->returnRental($rental);
    }
}
