<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 15:34
 */

namespace Seta\PenaltyBundle\Business;


use Seta\PenaltyBundle\Entity\Penalty;
use Seta\PenaltyBundle\Repository\PenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Exception\NotExpiredRentalException;
use Seta\UserBundle\Entity\User;
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
     * @var string Tiempo que tiene que pasar sin devolver un préstamo para sancionar al usuario.
     */
    private $penalty;

    /**
     * PenaltyService constructor.
     */
    public function __construct(EntityManager $manager, PenaltyRepository $penaltyRepository, $penalty)
    {
        $this->manager = $manager;
        $this->penaltyRepository = $penaltyRepository;
        $this->penalty = new \DateTime("-".$penalty);
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

        if ($from > $this->penalty) {
            $end = new \DateTime('next year september 1 midnight');
        } else {
            $time = $diff * 7;
            $end = new \DateTime($time.' days midnight');
        }

        return $end;
    }
}