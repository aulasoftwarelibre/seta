<?php

namespace spec\Seta\RentalBundle\Business;

use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\Exception\ExpiredRentalException;
use Seta\RentalBundle\Exception\FinishedRentalException;
use Seta\RentalBundle\Exception\NotRenewableRentalException;
use Seta\RentalBundle\Exception\TooEarlyRenovationException;
use Seta\RentalBundle\RentalEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RenewServiceSpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        Rental $rental
    )
    {
        $days_before_renovation = '2';
        $days_length_rental = '7';
        $this->beConstructedWith($manager, $dispatcher, $days_before_renovation, $days_length_rental);

        $rental->getIsRenewable()->willReturn(true);
        $rental->getDaysLeft()->willReturn(2);
        $rental->getIsExpired()->willReturn(false);
        $rental->getReturnAt()->willReturn(null);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Business\RenewService');
    }

    function it_can_renew_a_rental(
        Rental $rental,
        EntityManager $manager,
        EventDispatcherInterface $dispatcher
    )
    {
        $rental->getReturnAt()->shouldBeCalled();

        $newEnd = new \DateTime("9 days midnight");
        $rental->setEndAt($newEnd)->shouldBeCalled();

        $manager->persist($rental)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $dispatcher->dispatch(RentalEvents::LOCKER_RENEWED, Argument::type(RentalEvent::class))->shouldBeCalled();

        $this->renewRental($rental);
    }
    
    function it_cannot_renew_a_returned_rental(
        Rental $rental
    )
    {
        $rental->getReturnAt()->shouldBeCalled()->willReturn(new \DateTime());
        
        $this->shouldThrow(FinishedRentalException::class)->duringRenewRental($rental);
    }

    function it_cannot_renew_a_rental_too_early(
        Rental $rental
    )
    {
        $rental->getDaysLeft()->shouldBeCalled()->willReturn(3);

        $this->shouldThrow(TooEarlyRenovationException::class)->duringRenewRental($rental);
    }

    function it_cannot_renew_blocked_rentals(
        Rental $rental
    )
    {
        $rental->getIsRenewable()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(NotRenewableRentalException::class)->duringRenewRental($rental);
    }

    function it_cannot_renew_expired_rental(
        Rental $rental
    )
    {
        $rental->getIsExpired()->shouldBeCalled()->willReturn(true);

        $this->shouldThrow(ExpiredRentalException::class)->duringRenewRental($rental);
    }
}
