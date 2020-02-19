<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 11:27.
 */
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Locker;
use App\Services\PenalizeRentalInterface;
use App\Entity\Rental;
use App\Event\RentalEvent;
use App\Exception\FinishedRentalException;
use App\Events\RentalEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReturnService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var PenalizeRentalInterface
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
        PenalizeRentalInterface $penaltyService
    ) {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->penaltyService = $penaltyService;
    }

    /**
     * Return a Rental.
     *
     * @param Rental $rental
     */
    public function returnRental(Rental $rental)
    {
        if ($rental->getReturnAt()) {
            throw new FinishedRentalException();
        }

        if ($rental->getDaysLate() > 0) {
            $this->penaltyService->penalizeRental($rental);
        }

        $now = new \DateTime('now');
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
