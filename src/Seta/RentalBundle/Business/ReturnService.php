<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 11:27
 */

namespace Seta\RentalBundle\Business;


use Doctrine\ORM\EntityManagerInterface;
use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Business\TimePenaltyServiceInterface;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\Exception\FinishedRentalException;
use Seta\RentalBundle\RentalEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReturnService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var TimePenaltyServiceInterface
     */
    private $penaltyService;
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
        TimePenaltyServiceInterface $penaltyService
    )
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->penaltyService = $penaltyService;
    }

    /**
     * Return a Rental
     *
     * @param Rental $rental
     */
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
}
