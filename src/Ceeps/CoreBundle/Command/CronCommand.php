<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29/12/15
 * Time: 18:37
 */

namespace Ceeps\CoreBundle\Command;


use Ceeps\RentalBundle\Entity\Rental;
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
        $output->write("Enviando mensajes de renovación a los mensajes del ");
        $this->sendReminderEmail($output);
        $output->writeln(". Hecho.");
    }

    private function sendReminderEmail(OutputInterface $output)
    {
        $days = $this->getContainer()->getParameter('ceeps_core.notifications.reminder');
        $on = new \DateTime($days);
        $output->write($on->format('d/m/y'));

        $rentals = $this->getContainer()->get('tuconsigna.repository.rental')->getExpireOnDateRentals($on);
        /** @var Rental $rental */
        foreach ($rentals as $rental) {
            $this->getContainer()->get('seta_mailing')->sendRenewWarningEmail($rental);
        }
    }
}