<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Seta\PenaltyBundle\Business;


use Doctrine\ORM\EntityManager;
use Seta\PenaltyBundle\Entity\FinancialPenalty;
use Seta\PenaltyBundle\Repository\FinancialPenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;

class FinancialPenaltyService implements FinancialPenaltyServiceInterface
{
    /**
     * @var EntityManager
     */
    private $manager;
    /**
     * @var FinancialPenaltyRepository
     */
    private $penaltyRepository;

    /**
     * FinancialPenaltyService constructor.
     * @param EntityManager $manager
     * @param FinancialPenaltyRepository $penaltyRepository
     */
    public function __construct(EntityManager $manager, FinancialPenaltyRepository $penaltyRepository)
    {
        $this->manager = $manager;
        $this->penaltyRepository = $penaltyRepository;
    }

    /**
     * @param User $user
     * @param $amount
     */
    public function penalizeUser(User $user, $amount, $comment)
    {
        $user->setIsPenalized(true);

        /** @var FinancialPenalty $penalty */
        $penalty = $this->penaltyRepository->createNew();

        $penalty->setUser($user);
        $penalty->setAmmount($amount);
        $penalty->setComment($comment);

        $this->manager->persist($user);
        $this->manager->persist($penalty);
        $this->manager->flush();
    }

    public function penalizeRental(Rental $rental, $amount)
    {
        $comment = "Penalización automática por retraso al entregar la taquilla " . $rental->getLocker()->getCode();

        $user = $rental->getUser();
        $user->setIsPenalized(true);

        /** @var FinancialPenalty $penalty */
        $penalty = $this->penaltyRepository->createNew();

        $penalty->setUser($user);
        $penalty->setAmmount($amount);
        $penalty->setComment($comment);
        $penalty->setRental($rental);

        $this->manager->persist($user);
        $this->manager->persist($penalty);
        $this->manager->flush();
    }
}