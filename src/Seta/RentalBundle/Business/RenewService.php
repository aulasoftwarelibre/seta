<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 04:09.
 */
namespace Seta\RentalBundle\Business;

use Doctrine\ORM\EntityManagerInterface;
use Seta\PenaltyBundle\Exception\PenalizedFacultyException;
use Seta\PenaltyBundle\Exception\PenalizedUserException;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\Exception\ExpiredRentalException;
use Seta\RentalBundle\Exception\FinishedRentalException;
use Seta\RentalBundle\Exception\NotRenewableRentalException;
use Seta\RentalBundle\Exception\TooEarlyRenovationException;
use Seta\RentalBundle\RentalEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RenewService
{
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
     * @param EntityManagerInterface   $manager
     * @param EventDispatcherInterface $dispatcher
     * @param $days_before_renovation int
     * @param $days_length_rental int
     */
    public function __construct(
        EntityManagerInterface $manager,
        EventDispatcherInterface $dispatcher,
        $days_before_renovation,
        $days_length_rental
    ) {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->days_before_renovation = $days_before_renovation;
        $this->days_length_rental = $days_length_rental;
    }

    /**
     * Renueva el alquiler.
     *
     * @param Rental $rental
     *
     * @return Rental
     */
    public function renewRental(Rental $rental)
    {
        if ($rental->getReturnAt()) {
            throw new FinishedRentalException();
        }

        if ($rental->getUser()->getIsPenalized()) {
            throw new PenalizedUserException();
        }

        if ($rental->getUser()->getFaculty()->getIsEnabled() === false) {
            throw new PenalizedFacultyException();
        }

        if (!$rental->getIsRenewable()) {
            throw new NotRenewableRentalException();
        }

        if ($rental->getIsExpired()) {
            throw new ExpiredRentalException();
        }

        if ($rental->getDaysLeft() > $this->days_before_renovation) {
            throw new TooEarlyRenovationException();
        }

        $left = $rental->getDaysLeft() + $this->days_length_rental;
        $rental->setEndAt(new \DateTime($left.' days midnight'));

        $this->manager->persist($rental);
        $this->manager->flush();

        $event = new RentalEvent($rental);
        $this->dispatcher->dispatch(RentalEvents::LOCKER_RENEWED, $event);

        return $rental;
    }
}
