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
use Ceeps\LockerBundle\Exception\NoFreeLockerException;
use Ceeps\LockerBundle\Repository\LockerRepository;
use Ceeps\PenaltyBundle\Exception\PenalizedUserException;
use Ceeps\PenaltyBundle\Exception\TooManyLockersRentedException;
use Ceeps\RentalBundle\Entity\Queue;
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

    public function __construct(
        EntityManagerInterface $manager,
        RentalRepository $rentalRepository,
        LockerRepository $lockerRepository,
        QueueRepository $queueRepository
    )
    {
        $this->manager = $manager;
        $this->rentalRepository = $rentalRepository;
        $this->lockerRepository = $lockerRepository;
        $this->queueRepository = $queueRepository;
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

            throw new NoFreeLockerException();
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
}