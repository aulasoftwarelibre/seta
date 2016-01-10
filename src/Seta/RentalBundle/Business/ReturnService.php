<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 11:27
 */

namespace Seta\RentalBundle\Business;


use Seta\LockerBundle\Entity\Locker;
use Seta\LockerBundle\Exception\NotRentedLockerException;
use Seta\PenaltyBundle\Business\PenaltyServiceInterface;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\Exception\FinishedRentalException;
use Seta\RentalBundle\RentalEvents;
use Seta\RentalBundle\Repository\RentalRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReturnService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var PenaltyServiceInterface
     */
    private $penaltyService;
    /**
     * @var RentalRepositoryInterface
     */
    private $rentalRepository;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;


    /**
     * ReturnService constructor.
     */
    public function __construct(
        EntityManagerInterface $manager,
        EventDispatcherInterface $dispatcher,
        PenaltyServiceInterface $penaltyService,
        RentalRepositoryInterface $rentalRepository
    )
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->penaltyService = $penaltyService;
        $this->rentalRepository = $rentalRepository;
    }

    public function returnRental(Rental $rental)
    {
        if ($rental->getReturnAt()) {
            throw new FinishedRentalException;
        }

        $now = new \DateTime('now');
        if ($rental->getEndAt() < $now) {
            $this->penaltyService->penalizeRental($rental);
        }

        $rental->setReturnAt($now);

        $locker = $rental->getLocker();
        $locker->setOwner(null);
        $locker->setStatus(Locker::AVAILABLE);

        $this->manager->persist($rental);
        $this->manager->persist($locker);
        $this->manager->flush();

        $event = new RentalEvent($rental);
        $this->dispatcher->dispatch(RentalEvents::LOCKER_RETURNED, $event);
    }

    /**
     * Return a Locker
     *
     * @param Locker $locker
     */
    public function returnLocker(Locker $locker)
    {
        if ($locker->getStatus() !== Locker::RENTED) {
            throw new NotRentedLockerException;
        }

        /** @var Rental $rental */
        $rental = $this->rentalRepository->getCurrentRental($locker);

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
