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

    public function sendNewRentalEmail(Rental $rental)
    {
        $this->systemMailer->send('MailerBundle:new-rental', ['rental' => $rental]);
    }

    public function sendNoRenewRentalEmail(Rental $rental)
    {
        $this->systemMailer->send('MailerBundle:no-renew-rental', ['rental' => $rental]);
    }

    public function sendPenaltyWarning(Rental $rental)
    {
        $this->systemMailer->send('MailerBundle:penalty-warning', ['rental' => $rental]);
    }

    public function sendRenewRentalEmail(Rental $rental)
    {
        $this->systemMailer->send('MailerBundle:renew-rental', ['rental' => $rental]);
    }

    public function sendRenewWarningEmail(Rental $rental)
    {
        $this->systemMailer->send('MailerBundle:renew-warning', ['rental' => $rental]);
    }

    public function sendSuspensionWarning(Rental $rental)
    {
        $this->systemMailer->send('MailerBundle:suspension-warning', ['rental' => $rental]);
    }
}
