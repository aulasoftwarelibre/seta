<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 04:09
 */

namespace Ceeps\RentalBundle\Business;


use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\LockerBundle\Exception\NotRentedLockerException;
use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\RentalBundle\Exception\ExpiredRentalException;
use Ceeps\RentalBundle\Exception\NotRenewableRentalException;
use Ceeps\RentalBundle\Exception\TooEarlyRenovationException;
use Ceeps\RentalBundle\Repository\RentalRepositoryInterface;
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

    /**
     * RenewService constructor.
     */
    public function __construct(
        EntityManagerInterface $manager,
        RentalRepositoryInterface $rentalRepository
    )
    {
        $this->manager = $manager;
        $this->rentalRepository = $rentalRepository;
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

        $newend = $rental->getEndAt()->add(new \DateInterval("P7D"));
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

        if ($limit->diff($rental->getEndAt())->days >= 2) {
            throw new TooEarlyRenovationException;
        }

        return true;
    }
}