<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\EventListener;


use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use App\Entity\FinancialPenalty;
use App\Entity\Penalty;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class NewFinancialPenaltySubscriber implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => 'penalizeUser',
        ];
    }

    public function penalizeUser(GenericEvent $event)
    {
        /** @var FinancialPenalty $subject */
        $subject = $event->getSubject();

        if ($subject instanceof FinancialPenalty === false) {
            return;
        }

        if ($subject->getStatus() === Penalty::ACTIVE) {
            $subject->getUser()->setIsPenalized(true);
        }
    }
}
