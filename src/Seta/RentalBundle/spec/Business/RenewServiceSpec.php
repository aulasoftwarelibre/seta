<?php

namespace spec\Seta\RentalBundle\Business;

use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Seta\PenaltyBundle\Exception\PenalizedFacultyException;
use Seta\PenaltyBundle\Exception\PenalizedUserException;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\Exception\ExpiredRentalException;
use Seta\RentalBundle\Exception\FinishedRentalException;
use Seta\RentalBundle\Exception\NotRenewableRentalException;
use Seta\RentalBundle\Exception\TooEarlyRenovationException;
use Seta\RentalBundle\RentalEvents;
use Seta\UserBundle\Entity\Faculty;
use Seta\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RenewServiceSpec extends ObjectBehavior
{
    const DAYS_BEFORE_RENOVATION = 2;
    const DAYS_LENGTH_RENTAL = 7;

    public function let(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        Faculty $faculty,
        Rental $rental,
        User $user
    ) {
        $this->beConstructedWith($manager, $dispatcher, self::DAYS_BEFORE_RENOVATION, self::DAYS_LENGTH_RENTAL);

        $rental->getIsRenewable()->willReturn(true);
        $rental->getDaysLeft()->willReturn(2);
        $rental->getIsExpired()->willReturn(false);
        $rental->getReturnAt()->willReturn(null);
        $rental->getUser()->willReturn($user);

        $user->getIsPenalized()->willReturn(false);
        $user->getFaculty()->willReturn($faculty);

        $faculty->getIsEnabled()->willReturn(true);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Business\RenewService');
    }

    public function it_can_renew_a_rental(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        Rental $rental
    ) {
        $newEnd = new \DateTime('9 days midnight');
        $rental->setEndAt($newEnd)->shouldBeCalled();

        $manager->persist($rental)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $event = new RentalEvent($rental->getWrappedObject());
        $dispatcher->dispatch(RentalEvents::LOCKER_RENEWED, $event)->shouldBeCalled();

        $this->renewRental($rental);
    }

    public function it_cannot_renew_a_returned_rental(
        Rental $rental
    ) {
        $rental->getReturnAt()->shouldBeCalled()->willReturn(new \DateTime());

        $this->shouldThrow(FinishedRentalException::class)->duringRenewRental($rental);
    }

    public function it_cannot_renew_a_penalized_user(
        Rental $rental,
        User $user
    ) {
        $user->getIsPenalized()->shouldBeCalled()->willReturn(true);

        $this->shouldThrow(PenalizedUserException::class)->duringRenewRental($rental);
    }

    public function it_cannot_renew_a_disabled_faculty(
        Faculty $faculty,
        Rental $rental
    ) {
        $faculty->getIsEnabled()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(PenalizedFacultyException::class)->duringRenewRental($rental);
    }

    public function it_cannot_renew_a_rental_too_early(
        Rental $rental
    ) {
        $rental->getDaysLeft()->shouldBeCalled()->willReturn(3);

        $this->shouldThrow(TooEarlyRenovationException::class)->duringRenewRental($rental);
    }

    public function it_cannot_renew_blocked_rentals(
        Rental $rental
    ) {
        $rental->getIsRenewable()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(NotRenewableRentalException::class)->duringRenewRental($rental);
    }

    public function it_cannot_renew_expired_rental(
        Rental $rental
    ) {
        $rental->getIsExpired()->shouldBeCalled()->willReturn(true);

        $this->shouldThrow(ExpiredRentalException::class)->duringRenewRental($rental);
    }
}
