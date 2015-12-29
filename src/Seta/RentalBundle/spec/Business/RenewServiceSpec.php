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

class RenewServiceSpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        RentalRepository $rentalRepository,
        Rental $rental,
        Locker $locker
    )
    {
        $this->beConstructedWith($manager, $rentalRepository);

        $locker->getStatus()->willReturn(Locker::RENTED);

        $rentalRepository->getCurrentRental($locker)->willReturn($rental);
        $rental->getIsRenewable()->willReturn(true);
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
        $end = new \DateTime("1 days 23:59:59");
        $newEnd = new \DateTime("8 days 23:59:59");

        $rental->getEndAt()->shouldBeCalled()->willReturn($end);
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
        $end = new \DateTime("2 days 23:59:59");
        $rental->getEndAt()->shouldBeCalled()->willReturn($end);

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
        $end = new \DateTime("-1 days 23:59:59");
        $rental->getEndAt()->shouldBeCalled()->willReturn($end);

        $this->shouldThrow(ExpiredRentalException::class)->duringRenewLocker($locker);
    }
}
