<?php

namespace spec\Seta\RentalBundle\Business;

use Seta\LockerBundle\Entity\Locker;
use Seta\LockerBundle\Exception\BusyLockerException;
use Seta\LockerBundle\Exception\NotFreeLockerException;
use Seta\LockerBundle\Exception\NotRentedLockerException;
use Seta\LockerBundle\Repository\LockerRepository;
use Seta\PenaltyBundle\Business\PenaltyService;
use Seta\PenaltyBundle\Exception\PenalizedUserException;
use Seta\PenaltyBundle\Exception\TooManyLockersRentedException;
use Seta\RentalBundle\Entity\Queue;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Repository\QueueRepository;
use Seta\RentalBundle\Repository\RentalRepository;
use Seta\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RentalServiceSpec extends ObjectBehavior
{
    function let(
        EntityManager $manager,
        RentalRepository $rentalRepository,
        LockerRepository $lockerRepository,
        QueueRepository $queueRepository,
        User $user,
        Rental $rental,
        Locker $locker
    )
    {
        $this->beConstructedWith($manager, $rentalRepository, $lockerRepository, $queueRepository);
        $user->getLockers()->willReturn(new ArrayCollection());
        $user->getIsPenalized()->willReturn(false);
        $user->getQueue()->willReturn(null);

        $locker->getOwner()->willReturn(null);
        $locker->getStatus()->willReturn(Locker::RENTED);

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
        $locker->getOwner()->willReturn(new User());

        $this->shouldThrow(BusyLockerException::class)->duringRentLocker($user, $locker);
    }

    function it_cannot_rent_without_free_lockers(
        User $user,
        Queue $queue,
        EntityManager $manager,
        LockerRepository $lockerRepository,
        QueueRepository $queueRepository
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
        Locker $locker,
        ArrayCollection $collection
    )
    {
        $user->getLockers()->willReturn($collection);
        $collection->count()->willReturn(1);

        $this->shouldThrow(TooManyLockersRentedException::class)->duringRentLocker($user, $locker);
    }
}
