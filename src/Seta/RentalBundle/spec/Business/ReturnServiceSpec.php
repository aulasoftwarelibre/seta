<?php

namespace spec\Seta\RentalBundle\Business;

use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Business\TimePenaltyService;
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
    public function let(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        TimePenaltyService $penaltyService,
        Locker $locker,
        Rental $rental
    ) {
        $this->beConstructedWith($manager, $dispatcher, $penaltyService);

        $rental->getIsRenewable()->willReturn(true);
        $rental->getLocker()->willReturn($locker);
        $rental->getReturnAt()->willReturn(null);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Business\ReturnService');
    }

    public function it_can_return_a_locker_before_due_date(
        Locker $locker,
        Rental $rental,
        EntityManager $manager,
        EventDispatcherInterface $dispatcher
    ) {
        $rental->getDaysLate()->shouldBeCalled()->willReturn(0);
        $rental->setReturnAt(Argument::type(\DateTime::class))->shouldBeCalled();

        $locker->setOwner(null)->shouldBeCalled();
        $locker->setStatus(Locker::AVAILABLE)->shouldBeCalled();

        $manager->persist($rental)->shouldBeCalled();
        $manager->persist($locker)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $dispatcher->dispatch(RentalEvents::LOCKER_RETURNED, Argument::type(RentalEvent::class))->shouldBeCalled();

        $this->returnRental($rental);
    }

    public function it_cannot_return_a_returned_rental(
        Rental $rental
    ) {
        $rental->getReturnAt()->shouldBeCalled()->willReturn(new \DateTime());

        $this->shouldThrow(FinishedRentalException::class)->duringReturnRental($rental);
    }

    public function it_can_return_a_locker_after_due_date(
        Locker $locker,
        Rental $rental,
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        TimePenaltyService $penaltyService
    ) {
        $rental->getDaysLate()->shouldBeCalled()->willReturn(1);
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
