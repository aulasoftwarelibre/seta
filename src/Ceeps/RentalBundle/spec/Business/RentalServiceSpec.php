<?php

namespace spec\Ceeps\RentalBundle\Business;

use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\LockerBundle\Exception\BusyLockerException;
use Ceeps\LockerBundle\Exception\NotFreeLockerException;
use Ceeps\LockerBundle\Exception\NotRentedLockerException;
use Ceeps\LockerBundle\Repository\LockerRepository;
use Ceeps\PenaltyBundle\Business\PenaltyService;
use Ceeps\PenaltyBundle\Exception\PenalizedUserException;
use Ceeps\PenaltyBundle\Exception\TooManyLockersRentedException;
use Ceeps\RentalBundle\Entity\Queue;
use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\RentalBundle\Repository\QueueRepository;
use Ceeps\RentalBundle\Repository\RentalRepository;
use Ceeps\UserBundle\Entity\User;
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
        PenaltyService $penaltyService,
        User $user,
        Rental $rental,
        Locker $locker
    )
    {
        $this->beConstructedWith($manager, $rentalRepository, $lockerRepository, $queueRepository, $penaltyService);
        $user->getLockers()->willReturn(new ArrayCollection());
        $user->getIsPenalized()->willReturn(false);
        $user->getQueue()->willReturn(null);

        $locker->getOwner()->willReturn(null);
        $locker->getStatus()->willReturn(Locker::RENTED);

        $rentalRepository->getCurrentRental($locker)->willReturn($rental);

    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ceeps\RentalBundle\Business\RentalService');
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
        $rental->setStartAt(Argument::any())->shouldBeCalled();
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
        $rental->setStartAt(Argument::any())->shouldBeCalled();
        $rental->setEndAt(Argument::any())->shouldBeCalled();

        $locker->setStatus(Locker::RENTED)->shouldBeCalled();
        $locker->setOwner($user)->shouldBeCalled();

        $manager->persist($locker)->shouldBeCalled();
        $manager->persist($rental)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->rentLocker($user);
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
        $lockerRepository->findOneFreeLocker()->shouldBeCalled()->willReturn(null);

        $queueRepository->createNew()->shouldBeCalled()->willReturn($queue);
        $queue->setUser($user)->shouldBeCalled();
        $manager->persist($queue)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->shouldThrow(NotFreeLockerException::class)->duringRentLocker($user);
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
