<?php

namespace spec\Seta\RentalBundle\Business;

use Seta\LockerBundle\Entity\Locker;
use Seta\LockerBundle\Exception\BusyLockerException;
use Seta\LockerBundle\Exception\NotFreeLockerException;
use Seta\LockerBundle\Repository\LockerRepository;
use Seta\PenaltyBundle\Exception\PenalizedUserException;
use Seta\RentalBundle\Exception\TooManyLockersRentedException;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Repository\RentalRepository;
use Seta\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RentalServiceSpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        RentalRepository $rentalRepository,
        LockerRepository $lockerRepository,
        User $user,
        Rental $rental,
        Locker $locker
    )
    {
        $days_length_rental = 7;
        $this->beConstructedWith($manager, $dispatcher, $lockerRepository, $rentalRepository, $days_length_rental);
        $user->getLocker()->willReturn(null);
        $user->getIsPenalized()->willReturn(false);
        $user->getQueue()->willReturn(null);

        $locker->getOwner()->willReturn(null);
        $locker->getStatus()->willReturn(Locker::AVAILABLE);

        $rentalRepository->getCurrentRental($locker)->willReturn($rental);
        $rental->getIsRenewable()->willReturn(true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Business\RentalService');
    }

    function it_can_rent_a_locker(
        User $user,
        Locker $locker,
        Rental $rental,
        EntityManager $manager,
        RentalRepository $rentalRepository
    )
    {
        $rentalRepository->createNew()->willReturn($rental);

        $rental->setUser($user)->shouldBeCalled();
        $rental->setLocker($locker)->shouldBeCalled();
        $rental->setEndAt(Argument::any())->shouldBeCalled();

        $locker->setStatus(Locker::RENTED)->shouldBeCalled();
        $locker->setOwner($user)->shouldBeCalled();

        $manager->persist($locker)->shouldBeCalled();
        $manager->persist($rental)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->rentLocker($user, $locker);
    }
    
    function it_can_rent_any_free_locker(
        User $user,
        Locker $locker,
        Rental $rental,
        EntityManager $manager,
        RentalRepository $rentalRepository,
        LockerRepository $lockerRepository
    )
    {
        $rentalRepository->createNew()->willReturn($rental);
        $lockerRepository->findOneFreeLocker()->shouldBeCalled()->willReturn($locker);

        $rental->setUser($user)->shouldBeCalled();
        $rental->setLocker($locker)->shouldBeCalled();
        $rental->setEndAt(Argument::any())->shouldBeCalled();

        $locker->setStatus(Locker::RENTED)->shouldBeCalled();
        $locker->setOwner($user)->shouldBeCalled();

        $manager->persist($locker)->shouldBeCalled();
        $manager->persist($rental)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->rentFirstFreeLocker($user);
    }

    function it_cannot_rent_busy_locker(
        User $user,
        Locker $locker
    )
    {
        $locker->getStatus()->willReturn(Locker::RENTED);

        $this->shouldThrow(BusyLockerException::class)->duringRentLocker($user, $locker);
    }

    function it_cannot_rent_without_free_lockers(
        User $user,
        LockerRepository $lockerRepository
    )
    {
        $lockerRepository->findOneFreeLocker()->shouldBeCalled()->willThrow(NotFreeLockerException::class);

        $this->shouldThrow(NotFreeLockerException::class)->duringRentFirstFreeLocker($user);
    }

    function it_cannot_rent_to_penalized_users(
        User $user,
        Locker $locker
    )
    {
        $user->getIsPenalized()->willReturn(true);
        
        $this->shouldThrow(PenalizedUserException::class)->duringRentLocker($user, $locker);
    }

    function it_cannot_rent_two_lockers_to_the_same_user(
        User $user,
        Locker $locker
    )
    {
        $user->getLocker()->willReturn($locker);

        $this->shouldThrow(TooManyLockersRentedException::class)->duringRentLocker($user, $locker);
    }
}
