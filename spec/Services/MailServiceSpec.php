<?php

namespace spec\Seta\MailerBundle\Services;

use Hautzi\SystemMailBundle\SystemMailer\SystemMailer;
use PhpSpec\ObjectBehavior;
use App\Entity\Rental;

class MailServiceSpec extends ObjectBehavior
{
    public function let(
        SystemMailer $systemMailer
    ) {
        $this->beConstructedWith($systemMailer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Services\MailService');
    }

    public function it_send_email(
        Rental $rental,
        SystemMailer $systemMailer
    ) {
        $message = 'Test email';
        $locale = 'es';

        $systemMailer->send($message, ['rental' => $rental], $locale)->shouldBeCalled();

        $this->sendEmail($rental, $message, $locale);
    }
}