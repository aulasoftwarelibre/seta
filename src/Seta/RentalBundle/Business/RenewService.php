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
use Seta\RentalBundle\Exception\ExpiredRentalException;
use Seta\RentalBundle\Exception\NotRenewableRentalException;
use Seta\RentalBundle\Exception\TooEarlyRenovationException;
use Seta\RentalBundle\Repository\RentalRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

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
    private $reminder;
    private $renovation;

    /**
     * RenewService constructor.
     */
    public function __construct(
        EntityManagerInterface $manager,
        RentalRepositoryInterface $rentalRepository,
        $reminder,
        $renovation
    )
    {
        $this->manager = $manager;
        $this->rentalRepository = $rentalRepository;
        $this->reminder = $reminder;
        $this->renovation = $renovation;
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

        $interval = \DateInterval::createFromDateString($this->renovation);
        $newend = $rental->getEndAt()->add($interval);
        $rental->setEndAt($newend);

        $this->manager->persist($rental);
        $this->manager->flush();
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

        $limit = new \DateTime('today 23:59:59');
        if ($limit > $rental->getEndAt()) {
            throw new ExpiredRentalException;
        }

        $limit = new \DateTime($this->reminder);
        if ($rental->getEndAt() > $limit) {
            throw new TooEarlyRenovationException;
        }

        return true;
    }
}
