<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 09:57
 */

namespace Ceeps\RentalBundle\Business;


use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\LockerBundle\Exception\BusyLockerException;
use Ceeps\LockerBundle\Exception\NotFreeLockerException;
use Ceeps\LockerBundle\Exception\NotRentedLockerException;
use Ceeps\LockerBundle\Exception\NotFoundLockerException;
use Ceeps\LockerBundle\Repository\LockerRepository;
use Ceeps\PenaltyBundle\Business\PenaltyService;
use Ceeps\PenaltyBundle\Exception\PenalizedUserException;
use Ceeps\PenaltyBundle\Exception\TooManyLockersRentedException;
use Ceeps\RentalBundle\Entity\Queue;
use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\RentalBundle\Exception\NotFoundRentalException;
use Ceeps\RentalBundle\Repository\QueueRepository;
use Ceeps\RentalBundle\Repository\RentalRepository;
use Ceeps\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

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
     * @var QueueRepository
     */
    private $queueRepository;
    /**
     * @var PenaltyService
     */
    private $penaltyService;

    public function __construct(
        EntityManagerInterface $manager,
        RentalRepository $rentalRepository,
        LockerRepository $lockerRepository,
        QueueRepository $queueRepository,
        PenaltyService $penaltyService
    )
    {
        $this->manager = $manager;
        $this->rentalRepository = $rentalRepository;
        $this->lockerRepository = $lockerRepository;
        $this->queueRepository = $queueRepository;
        $this->penaltyService = $penaltyService;
    }

    public function rentLocker(User $user, Locker $locker = null)
    {
        if ($user->getIsPenalized()) {
            throw new PenalizedUserException();
        }

        if ($locker && $locker->getOwner()) {
            throw new BusyLockerException();
        }

        if ($user->getLockers()->count() > 0) {
            throw new TooManyLockersRentedException();
        }

        if ($user->getQueue()) {
            $this->manager->remove($user->getQueue());
        }

        if (!$locker) {
            $locker = $this->lockerRepository->findOneFreeLocker();
        }

        if (!$locker) {
            /** @var Queue $queue */
            $queue = $this->queueRepository->createNew();
            $queue->setUser($user);
            $this->manager->persist($queue);
            $this->manager->flush();

            throw new NotFreeLockerException();
        }

        $rental = $this->rentalRepository->createNew();

        $rental->setUser($user);
        $rental->setLocker($locker);
        $rental->setStartAt(new \DateTime('now'));
        $rental->setEndAt(new \DateTime('+7 days'));

        $locker->setStatus(Locker::RENTED);
        $locker->setOwner($user);

        $this->manager->persist($locker);
        $this->manager->persist($rental);

        $this->manager->flush();
    }

    public function returnLocker(Locker $locker)
    {
        if ($locker->getStatus() !== Locker::RENTED) {
            throw new NotRentedLockerException;
        }

        /** @var Rental $rental */
        $rental = $this->rentalRepository->getCurrentRental($locker);
        if (!$rental) {
            throw new NotFoundRentalException;
        }

        $now = new \DateTime('now');
        if ($rental->getEndAt() < $now) {
            $this->penaltyService->penalizeRental($rental);
        }

        $rental->setReturnAt($now);
        $locker->setOwner(null);
        $locker->setStatus(Locker::AVAILABLE);

        $this->manager->persist($rental);
        $this->manager->persist($locker);
        $this->manager->flush();
    }
}