<?php

namespace spec\Seta\RentalBundle\Business;

use Seta\LockerBundle\Entity\Locker;
use Seta\LockerBundle\Exception\NotRentedLockerException;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Exception\ExpiredRentalException;
use Seta\RentalBundle\Exception\NotRenewableRentalException;
use Seta\RentalBundle\Exception\TooEarlyRenovationException;
use Seta\RentalBundle\Repository\RentalRepository;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RenewServiceSpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        RentalRepository $rentalRepository,
        Rental $rental,
        Locker $locker
    )
    {
        $days_before_renovation = '2';
        $days_length_rental = '7';
        $this->beConstructedWith($manager, $dispatcher, $rentalRepository, $days_before_renovation, $days_length_rental);

        $locker->getStatus()->willReturn(Locker::RENTED);

        $rentalRepository->getCurrentRental($locker)->willReturn($rental);
        $rental->getIsRenewable()->willReturn(true);
        $rental->getDaysLeft()->willReturn(2);
        $rental->getIsExpired()->willReturn(false);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Business\RenewService');
    }

    function it_can_renew_a_rental(
        Rental $rental,
        Locker $locker,
        EntityManager $manager
    )
    {
        $newEnd = new \DateTime("9 days midnight");
        $rental->setEndAt($newEnd)->shouldBeCalled();

        $manager->persist($rental)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->renewLocker($locker);
    }
    
    function it_cannot_renew_a_not_rented_locker(
        Locker $locker
    )
    {
        $locker->getStatus()->shouldBeCalled()->willReturn(Locker::AVAILABLE);
        
        $this->shouldThrow(NotRentedLockerException::class)->duringRenewLocker($locker);
    }

    function it_cannot_renew_a_rental_too_early(
        Rental $rental,
        Locker $locker
    )
    {
        $rental->getDaysLeft()->shouldBeCalled()->willReturn(3);

        $this->shouldThrow(TooEarlyRenovationException::class)->duringRenewLocker($locker);
    }

    function it_cannot_renew_blocked_rentals(
        Rental $rental,
        Locker $locker
    )
    {
        $rental->getIsRenewable()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(NotRenewableRentalException::class)->duringRenewLocker($locker);
    }

    function it_cannot_renew_expired_rental(
        Rental $rental,
        Locker $locker
    )
    {
        $rental->getIsExpired()->shouldBeCalled()->willReturn(true);

        $this->shouldThrow(ExpiredRentalException::class)->duringRenewLocker($locker);
    }
}
