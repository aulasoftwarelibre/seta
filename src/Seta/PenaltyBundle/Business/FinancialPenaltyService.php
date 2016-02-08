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

use Doctrine\Common\Persistence\ObjectManager;
use Seta\PenaltyBundle\Entity\FinancialPenalty;
use Seta\PenaltyBundle\Event\PenaltyEvent;
use Seta\PenaltyBundle\PenaltyEvents;
use Seta\PenaltyBundle\Repository\FinancialPenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FinancialPenaltyService implements PenalizeRentalInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var FinancialPenaltyRepository
     */
    private $penaltyRepository;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var
     */
    private $amount;

    /**
     * FinancialPenaltyService constructor.
     *
     * @param ObjectManager              $manager
     * @param FinancialPenaltyRepository $penaltyRepository
     * @param EventDispatcherInterface   $dispatcher
     * @param float                      $amount
     */
    public function __construct(
        ObjectManager $manager,
        FinancialPenaltyRepository $penaltyRepository,
        EventDispatcherInterface $dispatcher,
        $amount
    ) {
        $this->manager = $manager;
        $this->penaltyRepository = $penaltyRepository;
        $this->dispatcher = $dispatcher;
        $this->amount = $amount;
    }

    /**
     * @param Rental $rental
     */
    public function penalizeRental(Rental $rental)
    {
        $comment = 'Penalización automática por retraso al entregar la taquilla '.$rental->getLocker()->getCode();

        /** @var FinancialPenalty $penalty */
        $penalty = $this->penaltyRepository->createNew();

        $penalty->setUser($rental->getUser());
        $penalty->setAmount($this->amount);
        $penalty->setComment($comment);
        $penalty->setRental($rental);

        $this->manager->persist($penalty);
        $this->manager->flush();

        $event = new PenaltyEvent($penalty);
        $this->dispatcher->dispatch(PenaltyEvents::PENALTY_CREATED, $event);
    }
}
