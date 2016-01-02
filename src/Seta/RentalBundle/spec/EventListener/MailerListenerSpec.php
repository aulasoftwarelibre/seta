<?php

namespace spec\Seta\RentalBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\MailerBundle\Business\MailService;
use Seta\MailerBundle\Business\Message;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Event\RentalEvent;
use Seta\RentalBundle\RentalEvents;

class MailerListenerSpec extends ObjectBehavior
{
    function let(
        MailService $mailService,
        Rental $rental,
        RentalEvent $event
    )
    {
        $this->beConstructedWith($mailService);

        $event->getRental()->willReturn($rental);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\EventListener\MailerListener');
    }

    function it_has_suscribed_events()
    {
        $this->getSubscribedEvents()->shouldBeArray();
    }
    
    function it_send_new_locker_rented_email(
        MailService $mailService,
        RentalEvent $event,
        Rental $rental
    )
    {
        $event->getRental()->shouldBeCalled();

        $mailService->sendEmail($rental, Message::NEW_RENTAL_MESSAGE)->shouldBeCalled();

        $this->sendNewRentalEmail($event);
    }

    function it_send_renew_rental_email(
        MailService $mailService,
        RentalEvent $event,
        Rental $rental
    )
    {
        $event->getRental()->shouldBeCalled();

        $mailService->sendEmail($rental, Message::RENEW_RENTAL_MESSAGE)->shouldBeCalled();

        $this->sendRenewRentalEmail($event);
    }
}
