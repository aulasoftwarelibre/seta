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
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\PenaltyBundle\Event\PenaltyEvent;
use Seta\PenaltyBundle\PenaltyEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClosePenaltyService implements ClosePenaltyInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * ClosePenaltyService constructor.
     * @param ObjectManager $manager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(ObjectManager $manager, EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function closePenalty(Penalty $penalty)
    {
        $penalty->close();
        $this->manager->persist($penalty);
        $this->manager->flush();

        $event = new PenaltyEvent($penalty);
        $this->dispatcher->dispatch(PenaltyEvents::PENALTY_CLOSED, $event);
    }
}