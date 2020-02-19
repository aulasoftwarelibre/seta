<?php

namespace spec\Seta\RentalBundle\Services;

use App\Entity\Locker;
use App\Exception\BusyLockerException;
use App\Exception\NotFreeLockerException;
use App\Repository\LockerRepository;
use App\Exception\PenalizedFacultyException;
use App\Exception\PenalizedUserException;
use App\Exception\TooManyLockersRentedException;
use App\Entity\Rental;
use App\Repository\RentalRepository;
use App\Entity\Faculty;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RentalServiceSpec extends ObjectBehavior
{
    public function let(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        Faculty $faculty,
        RentalRepository $rentalRepository,
        LockerRepository $lockerRepository,
        User $user,
        Rental $rental,
        Locker $locker
    ) {
        $days_length_rental = 7;
        $this->beConstructedWith($manager, $dispatcher, $lockerRepository, $rentalRepository, $days_length_rental);
        $user->getLocker()->willReturn(null);
        $user->getIsPenalized()->willReturn(false);
        $user->getQueue()->willReturn(null);
        $user->getFaculty()->willReturn($faculty);

        $faculty->getIsEnabled()->willReturn(true);

        $locker->getOwner()->willReturn(null);
        $locker->getStatus()->willReturn(Locker::AVAILABLE);

        $rentalRepository->getCurrentRental($locker)->willReturn($rental);
        $rental->getIsRenewable()->willReturn(true);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Services\RentalService');
    }

    public function it_can_rent_a_locker(
        User $user,
        Locker $locker,
        Rental $rental,
        EntityManager $manager,
        RentalRepository $rentalRepository
    ) {
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

    public function it_can_rent_any_free_locker(
        Faculty $faculty,
        User $user,
        Locker $locker,
        Rental $rental,
        EntityManager $manager,
        RentalRepository $rentalRepository,
        LockerRepository $lockerRepository
    ) {
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

    public function it_cannot_rent_busy_locker(
        User $user,
        Locker $locker
    ) {
        $locker->getStatus()->willReturn(Locker::RENTED);

        $this->shouldThrow(BusyLockerException::class)->duringRentLocker($user, $locker);
    }

    public function it_cannot_rent_without_free_lockers(
        User $user,
        LockerRepository $lockerRepository
    ) {
        $lockerRepository->findOneFreeLocker()->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(NotFreeLockerException::class)->duringRentFirstFreeLocker($user);
    }

    public function it_cannot_rent_to_penalized_users(
        User $user,
        Locker $locker
    ) {
        $user->getIsPenalized()->willReturn(true);

        $this->shouldThrow(PenalizedUserException::class)->duringRentLocker($user, $locker);
    }

    public function it_cannot_rent_to_disabled_faculties(
        Faculty $faculty,
        Locker $locker,
        User $user
    ) {
        $faculty->getIsEnabled()->willReturn(false);

        $this->shouldThrow(PenalizedFacultyException::class)->duringRentLocker($user, $locker);
    }

    public function it_cannot_rent_two_lockers_to_the_same_user(
        User $user,
        Locker $locker
    ) {
        $user->getLocker()->willReturn($locker);

        $this->shouldThrow(TooManyLockersRentedException::class)->duringRentLocker($user, $locker);
    }
}
