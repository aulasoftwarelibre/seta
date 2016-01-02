<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 01/01/16
 * Time: 21:31
 */

namespace Seta\RentalBundle\EventListener;


use Seta\MailerBundle\Business\MailService;
use Seta\MailerBundle\Business\Message;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\RentalEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailerListener implements EventSubscriberInterface
{
    /**
     * @var MailService
     */
    private $mailService;

    /**
     * MailerListener constructor.
     * @param MailService $mailService
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * @param RentalEvent $event
     */
    public function sendNewRentalEmail(RentalEvent $event)
    {
        $this->mailService->sendEmail($event->getRental(), Message::NEW_RENTAL_MESSAGE);
    }

    /**
     * @param RentalEvent $event
     */
    public function sendRenewRentalEmail(RentalEvent $event)
    {
        $this->mailService->sendEmail($event->getRental(), Message::RENEW_RENTAL_MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            RentalEvents::LOCKER_RENTED => 'sendNewRentalEmail',
            RentalEvents::LOCKER_RENEWED => 'sendRenewRentalEmail',
        ];
    }
}