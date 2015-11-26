<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/11/15
 * Time: 19:19
 */

namespace AppBundle\EventListener;


use AppBundle\Event\LockerEvent;
use AppBundle\Event\TuConsignaEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailerListener implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * MailerListener constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

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
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            TuConsignaEvents::LOCKER_RENTED => 'sendEmailToUser',
        ];
    }

    public function sendEmailToUser(LockerEvent $event)
    {
        /** @var \Swift_Message $message */
        $message = $this->mailer->createMessage();
        $message->setSubject('Taquilla alquilada')
            ->setFrom('tuconsigna@gmail.uco.es')
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                'Enhorabuena ha alquilado la taquilla número ' .
                $event->getLocker()->getNumber()
            )
        ;
        $this->mailer->send($message);
    }
}