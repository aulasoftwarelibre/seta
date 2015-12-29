<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 15:34
 */

namespace Ceeps\PenaltyBundle\Business;


use Ceeps\PenaltyBundle\Entity\Penalty;
use Ceeps\PenaltyBundle\Repository\PenaltyRepository;
use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\RentalBundle\Exception\NotExpiredRentalException;
use Ceeps\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class PenaltyService implements PenaltyServiceInterface
{
    /**
     * @var EntityManager
     */
    private $manager;
    /**
     * @var PenaltyRepository
     */
    private $penaltyRepository;

    /**
     * PenaltyService constructor.
     */
    public function __construct(EntityManager $manager, PenaltyRepository $penaltyRepository)
    {
        $this->manager = $manager;
        $this->penaltyRepository = $penaltyRepository;
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

        /** @var Penalty $penalty */
        $penalty = $this->penaltyRepository->createFromData($user, $end, $comment);

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

        /** @var Penalty $penalty */
        $penalty = $this->penaltyRepository->createFromData($user, $end, $comment, $rental);

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
        $until = $rental->getEndAt();
        $from = new \DateTime('now');

        if ($from < $until) {
            throw new NotExpiredRentalException;
        }
        $diff = $from->diff($until)->days + 1;

        if ($diff > 7) {
            $end = new \DateTime('next year september 1 midnight');
        } else {
            $time = $diff * 7;
            $end = new \DateTime($time.' days midnight');
        }

        return $end;
    }
}