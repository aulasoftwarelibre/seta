<?php

namespace App\Services;

class SendNotificationService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(array $recipients, $subject, $body)
    {
        $message = \Swift_Message::newInstance()
            ->setBcc($recipients)
            ->setFrom(['seta@consejo-eps.uco.es' => 'Servicio Electrónico de Taquillas'])
            ->setReplyTo('noreply@consejo-eps.uco.es')
            ->setSubject($subject)
            ->setBody($body, 'text/html')
            ;

        $this->mailer->send($message);
    }
}
