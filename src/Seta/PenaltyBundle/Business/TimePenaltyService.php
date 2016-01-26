<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 15:34
 */

namespace Seta\PenaltyBundle\Business;


use Seta\PenaltyBundle\Entity\TimePenalty;
use Seta\PenaltyBundle\Repository\TimePenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Exception\NotExpiredRentalException;
use Seta\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class TimePenaltyService implements TimePenaltyServiceInterface
{
    /**
     * @var EntityManager
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
     * PenaltyService constructor.
     * @param EntityManager $manager
     * @param TimePenaltyRepository $penaltyRepository
     * @param $days_before_penalty string Días a partir del cual el retraso conlleva sanción
     */
    public function __construct(EntityManager $manager, TimePenaltyRepository $penaltyRepository, $days_before_penalty)
    {
        $this->manager = $manager;
        $this->penaltyRepository = $penaltyRepository;
        $this->days_before_suspension = $days_before_penalty;
    }

    /**
     * Crea una sanción a un usuario manualmente
     *
     * @param User $user
     * @param \DateTime $end
     * @param $comment
     */
    public function penalizeUser(User $user, \DateTime $end, $comment)
    {
        $user->setIsPenalized(true);

        /** @var TimePenalty $penalty */
        $penalty = $this->penaltyRepository->createNew();
        $penalty->setUser($user);
        $penalty->setEndAt($end);
        $penalty->setComment($comment);

        $this->manager->persist($user);
        $this->manager->persist($penalty);
        $this->manager->flush();
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

        $user = $rental->getUser();
        $user->setIsPenalized(true);

        /** @var TimePenalty $penalty */
        $penalty = $this->penaltyRepository->createNew();
        $penalty->setUser($user);
        $penalty->setEndAt($end);
        $penalty->setComment($comment);
        $penalty->setRental($rental);

        $this->manager->persist($user);
        $this->manager->persist($penalty);
        $this->manager->flush();
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
