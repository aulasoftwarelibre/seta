<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 11:27
 */

namespace Ceeps\RentalBundle\Business;


use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\LockerBundle\Exception\NotRentedLockerException;
use Ceeps\PenaltyBundle\Business\PenaltyServiceInterface;
use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\RentalBundle\Exception\NotFoundRentalException;
use Ceeps\RentalBundle\Repository\RentalRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

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
     * ReturnService constructor.
     */
    public function __construct(
        EntityManagerInterface $manager,
        PenaltyServiceInterface $penaltyService,
        RentalRepositoryInterface $rentalRepository
    )
    {
        $this->manager = $manager;
        $this->penaltyService = $penaltyService;
        $this->rentalRepository = $rentalRepository;
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