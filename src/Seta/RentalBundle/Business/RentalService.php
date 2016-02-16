<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 09:57.
 */
namespace Seta\RentalBundle\Business;

use Doctrine\ORM\EntityManagerInterface;
use Seta\LockerBundle\Entity\Locker;
use Seta\LockerBundle\Entity\Zone;
use Seta\LockerBundle\Exception\BusyLockerException;
use Seta\LockerBundle\Exception\NotFreeLockerException;
use Seta\LockerBundle\Exception\NotFreeZoneLockerException;
use Seta\LockerBundle\Repository\LockerRepository;
use Seta\PenaltyBundle\Exception\PenalizedFacultyException;
use Seta\PenaltyBundle\Exception\PenalizedUserException;
use Seta\RentalBundle\Exception\TooManyLockersRentedException;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\RentalEvents;
use Seta\RentalBundle\Repository\RentalRepository;
use Seta\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RentalService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var RentalRepository
     */
    private $rentalRepository;
    /**
     * @var LockerRepository
     */
    private $lockerRepository;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var int
     */
    private $days_length_rental;

    /**
     * RentalService constructor.
     *
     * @param EntityManagerInterface   $manager
     * @param EventDispatcherInterface $dispatcher
     * @param LockerRepository         $lockerRepository
     * @param RentalRepository         $rentalRepository
     * @param int                      $days_length_rental
     */
    public function __construct(
        EntityManagerInterface $manager,
        EventDispatcherInterface $dispatcher,
        LockerRepository $lockerRepository,
        RentalRepository $rentalRepository,
        $days_length_rental
    ) {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->lockerRepository = $lockerRepository;
        $this->rentalRepository = $rentalRepository;
        $this->days_length_rental = $days_length_rental;
    }

    public function rentFirstFreeLocker(User $user)
    {
        $locker = $this->lockerRepository->findOneFreeLocker();

        if (!$locker) {
            throw new NotFreeLockerException;
        }

        return $this->rentLocker($user, $locker);
    }

    public function rentFirstFreeZoneLocker(User $user, Zone $zone)
    {
        $locker = $this->lockerRepository->findOneFreeZoneLocker($zone);

        if (!$locker) {
            throw new NotFreeZoneLockerException;
        }

        return $this->rentLocker($user, $locker);
    }

    public function rentLocker(User $user, Locker $locker)
    {
        if ($user->getIsPenalized()) {
            throw new PenalizedUserException;
        }

        if ($user->getFaculty()->getIsEnabled() === false) {
            throw new PenalizedFacultyException;
        }

        if ($locker->getStatus() != Locker::AVAILABLE) {
            throw new BusyLockerException;
        }

        if ($user->getLocker()) {
            throw new TooManyLockersRentedException;
        }

        /** @var Rental $rental */
        $rental = $this->rentalRepository->createNew();

        $rental->setUser($user);
        $rental->setLocker($locker);
        $rental->setEndAt(new \DateTime($this->days_length_rental.' days'));

        $locker->setStatus(Locker::RENTED);
        $locker->setOwner($user);

        $this->manager->persist($locker);
        $this->manager->persist($rental);
        $this->manager->flush();

        $event = new RentalEvent($rental);
        $this->dispatcher->dispatch(RentalEvents::LOCKER_RENTED, $event);

        return $rental;
    }
}
