<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29/12/15
 * Time: 12:13
 */

namespace Seta\MailerBundle\Business;


use Seta\RentalBundle\Entity\Rental;
use Hautzi\SystemMailBundle\SystemMailer\SystemMailer;

class MailService
{
    /**
     * @var SystemMailer
     */
    private $systemMailer;

    /**
     * MailService constructor.
     */
    public function __construct(SystemMailer $systemMailer)
    {
        $this->systemMailer = $systemMailer;
    }

    public function sendEmail(Rental $rental, $message, $locale = 'es')
    {
        $this->systemMailer->send($message, ['rental' => $rental], $locale);
    }
}
