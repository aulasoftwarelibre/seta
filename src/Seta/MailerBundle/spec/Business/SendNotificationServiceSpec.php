<?php

namespace spec\Seta\MailerBundle\Business;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use App\Repository\RentalRepositoryInterface;
use App\Repository\UserRepository;

class SendNotificationServiceSpec extends ObjectBehavior
{
    function let(
        \Swift_Mailer $mailer,
        RentalRepositoryInterface $rentalRepository,
        UserRepository $userRepository
    )
    {
        $this->beConstructedWith($mailer, $rentalRepository, $userRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Services\SendNotificationService');
    }

    function it_send_email_all_users(
        \Swift_Mailer $mailer
    )
    {
        $recipients = ['test@test.com'];
        $subject = 'subject';
        $body = 'body';

        $mailer->send(Argument::type(\Swift_Message::class))->shouldBeCalled();

        $this->send($recipients, $subject, $body);
    }

}
