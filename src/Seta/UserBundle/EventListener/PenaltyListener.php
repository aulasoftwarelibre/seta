<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Seta\UserBundle\EventListener;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectManager;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\PenaltyBundle\Event\PenaltyEvent;
use Seta\PenaltyBundle\PenaltyEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PenaltyListener implements EventSubscriberInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * PenaltyListener constructor.
     *
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param PenaltyEvent $event
     */
    public function createdPenalty(PenaltyEvent $event)
    {
        $user = $event->getPenalty()->getUser();
        $user->setIsPenalized(true);

        $this->manager->persist($user);
        $this->manager->flush();
    }

    /**
     * @param PenaltyEvent $event
     */
    public function closedPenalty(PenaltyEvent $event)
    {
        $user = $event->getPenalty()->getUser();
        $criteria = Criteria::create()->where(Criteria::expr()->eq('status', Penalty::ACTIVE));

        if ($user->getPenalties()->matching($criteria)->count() === 0) {
            $user->setIsPenalized(false);

            $this->manager->persist($user);
            $this->manager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PenaltyEvents::PENALTY_CREATED => 'createdPenalty',
            PenaltyEvents::PENALTY_CLOSED => 'closedPenalty',
        ];
    }
}
