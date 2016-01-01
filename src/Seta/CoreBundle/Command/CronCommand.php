<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29/12/15
 * Time: 18:37
 */

namespace Seta\CoreBundle\Command;


use Seta\MailerBundle\Business\Message;
use Seta\RentalBundle\Entity\Rental;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('seta:cron:run')
            ->setDescription('Envía las notificaciones a los usuarios')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time = $this->getContainer()->getParameter('seta_core.notifications.reminder');
        $on = new \DateTime($time);
        $output->write("Enviando mensajes de renovación... ");
        $total = $this->sendReminderEmail(Message::RENEW_WARNING_MESSAGE, $on);
        $output->writeln("Hecho: [${total}].");

        $on = new \DateTime("yesterday");
        $output->write("Enviando mensajes de caducidad... ");
        $total = $this->sendReminderEmail(Message::PENALTY_WARNING_MESSAGE, $on);
        $output->writeln("Hecho: [${total}].");

        $time = $this->getContainer()->getParameter('seta_core.notifications.suspension');
        $on = new \DateTime("-".$time);
        $output->write("Enviando mensajes de suspensión... ");
        $this->sendReminderEmail(Message::SUSPENSION_WARNING_MESSAGE, $on);
        $output->writeln("Hecho: [${total}].");
    }

    private function sendReminderEmail($type, $on)
    {
        $rentals = $this->getContainer()->get('seta.repository.rental')->getExpireOnDateRentals($on);
        /** @var Rental $rental */
        foreach ($rentals as $rental) {
            $this->getContainer()->get('seta_mailing')->sendEmail($rental, $type);
        }

        return count($rentals);
    }
}
