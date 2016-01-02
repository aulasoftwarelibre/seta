<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 04:09
 */

namespace Seta\RentalBundle\Business;


use Seta\LockerBundle\Entity\Locker;
use Seta\LockerBundle\Exception\NotRentedLockerException;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\Exception\ExpiredRentalException;
use Seta\RentalBundle\Exception\NotRenewableRentalException;
use Seta\RentalBundle\Exception\TooEarlyRenovationException;
use Seta\RentalBundle\RentalEvents;
use Seta\RentalBundle\Repository\RentalRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RenewService
{
    /**
     * @var RentalRepositoryInterface
     */
    private $rentalRepository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var int
     */
    private $days_before_renovation;
    /**
     * @var int
     */
    private $days_length_rental;

    /**
     * RenewService constructor.
     *
     * @param EntityManagerInterface $manager
     * @param EventDispatcherInterface $dispatcher
     * @param RentalRepositoryInterface $rentalRepository
     * @param $days_before_renovation int
     * @param $days_length_rental int
     */
    public function __construct(
        EntityManagerInterface $manager,
        EventDispatcherInterface $dispatcher,
        RentalRepositoryInterface $rentalRepository,
        $days_before_renovation,
        $days_length_rental
    )
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->rentalRepository = $rentalRepository;
        $this->days_before_renovation = $days_before_renovation;
        $this->days_length_rental = $days_length_rental;
    }

    /**
     * Renew the rentals locker
     *
     * @param Locker $locker
     */
    public function renewLocker(Locker $locker)
    {
        if ($locker->getStatus() !== Locker::RENTED) {
            throw new NotRentedLockerException;
        }

        /** @var Rental $rental */
        $rental = $this->rentalRepository->getCurrentRental($locker);

        $this->checkExpiration($rental);

        $left = $rental->getDaysLeft() + $this->days_length_rental;
        $rental->setEndAt(new \DateTime($left." days midnight"));

        $this->manager->persist($rental);
        $this->manager->flush();

        $event = new RentalEvent($rental);
        $this->dispatcher->dispatch(RentalEvents::LOCKER_RENEWED, $event);
    }

    /**
     * Check if the rental can be renovated
     *
     * @param $rental
     * @return boolean Always 'true' or Exception
     * @throws NotRenewableRentalException
     * @throws ExpiredRentalException
     * @throws TooEarlyRenovationException
     */
    private function checkExpiration(Rental $rental)
    {
        if (!$rental->getIsRenewable()) {
            throw new NotRenewableRentalException;
        }

        if ($rental->getIsExpired()) {
            throw new ExpiredRentalException;
        }

        if ($rental->getDaysLeft() > $this->days_before_renovation) {
            throw new TooEarlyRenovationException;
        }

        return true;
    }
}
