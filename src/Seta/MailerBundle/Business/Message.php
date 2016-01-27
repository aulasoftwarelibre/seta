<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 31/12/15
 * Time: 04:17.
 */
namespace Seta\MailerBundle\Business;

final class Message
{
    const NEW_RENTAL_MESSAGE = 'SetaMailer:new-rental';
    const PENALTY_WARNING_MESSAGE = 'SetaMailer:penalty-warning';
    const RENEW_RENTAL_MESSAGE = 'SetaMailer:renew-rental';
    const RENEW_WARNING_MESSAGE = 'SetaMailer:renew-warning';
    const SUSPENSION_WARNING_MESSAGE = 'SetaMailer:suspension-warning';
}
