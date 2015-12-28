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

    public function penalizeUser(User $user, \DateTime $end, $comment)
    {
        /** @var Penalty $penalty */
        $penalty = $this->penaltyRepository->createNew();
        $penalty->setEndAt($end);
        $penalty->setComment($comment);
        $penalty->setUser($user);

        $user->setIsPenalized(true);

        $this->manager->persist($penalty);
        $this->manager->persist($user);
        $this->manager->flush();
    }

    public function penalizeRental(Rental $rental)
    {
        $now = new \DateTime('now');
        if ($rental->getEndAt() > $now) return;

        $end = $this->calculateEnd($now, $rental->getEndAt());
        $comment = "Bloqueo automático por retraso al entregar la taquilla " . $rental->getLocker()->getCode();

        $user = $rental->getUser();
        $user->setIsPenalized(true);
        $this->manager->persist($user);

        /** @var Penalty $penalty */
        $penalty = $this->penaltyRepository->createNew();
        $penalty->setComment($comment);
        $penalty->setEndAt($end);
        $penalty->setRental($rental);
        $penalty->setUser($user);

        $this->manager->persist($penalty);
        $this->manager->flush();
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $until
     * @return \DateTime
     */
    private function calculateEnd($from, $until)
    {
        $diff = $from->diff($until)->days + 1;

        if ($diff > 7) {
            $end = new \DateTime('next year september 1');
        } else {
            $time = $diff * 7;
            $end = new \DateTime($time.' days 23:59:59');
        }

        return $end;
    }
}