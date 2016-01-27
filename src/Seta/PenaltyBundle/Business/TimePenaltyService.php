<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 15:34
 */

namespace Seta\PenaltyBundle\Business;


use Doctrine\Common\Persistence\ObjectManager;
use Seta\PenaltyBundle\Entity\TimePenalty;
use Seta\PenaltyBundle\Event\PenaltyEvent;
use Seta\PenaltyBundle\PenaltyEvents;
use Seta\PenaltyBundle\Repository\TimePenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Exception\NotExpiredRentalException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TimePenaltyService implements PenalizeRentalInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var TimePenaltyRepository
     */
    private $penaltyRepository;
    /**
     * @var string Días a partir del cual el retraso conlleva sanción.
     */
    private $days_before_suspension;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * PenaltyService constructor.
     * @param ObjectManager $manager
     * @param TimePenaltyRepository $penaltyRepository
     * @param EventDispatcherInterface $dispatcher
     * @param $days_before_penalty string Días a partir del cual el retraso conlleva sanción
     */
    public function __construct(
        ObjectManager $manager,
        TimePenaltyRepository $penaltyRepository,
        EventDispatcherInterface $dispatcher,
        $days_before_penalty
    )
    {
        $this->manager = $manager;
        $this->penaltyRepository = $penaltyRepository;
        $this->dispatcher = $dispatcher;
        $this->days_before_suspension = $days_before_penalty;
    }

    /**
     * Crea y calcula una nueva sanción a través de los datos de un alquiler
     *
     * @param Rental $rental
     */
    public function penalizeRental(Rental $rental)
    {
        $end = $this->calculatePenalty($rental);
        $comment = "Bloqueo automático por retraso al entregar la taquilla " . $rental->getLocker()->getCode();

        /** @var TimePenalty $penalty */
        $penalty = $this->penaltyRepository->createNew();

        $user = $rental->getUser();
        $penalty->setUser($user);
        $penalty->setEndAt($end);
        $penalty->setComment($comment);
        $penalty->setRental($rental);

        $this->manager->persist($penalty);
        $this->manager->flush();

        $event = new PenaltyEvent($penalty);
        $this->dispatcher->dispatch(PenaltyEvents::PENALTY_CREATED, $event);
    }

    /**
     * Calcula la fecha de finalización de la sanción
     *
     * @param Rental $rental
     * @return \DateTime
     */
    public function calculatePenalty(Rental $rental)
    {
        $late = $rental->getDaysLate();

        if ($late === 0) {
            throw new NotExpiredRentalException;
        }

        if ($late >= $this->days_before_suspension) {
            $end = TimePenalty::getEndSeasonPenalty();
        } else {
            $time = $late * 7;
            $end = new \DateTime($time.' days midnight');
        }

        return $end;
    }
}
