<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29/12/15
 * Time: 18:37.
 */
namespace Seta\CoreBundle\Command;

use Seta\MailerBundle\Business\Message;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\RentalBundle\Entity\Rental;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class CronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('seta:cron:run')
            ->setDescription('Envía las notificaciones a los usuarios')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        // The mailer service needs the default locale
        $locale = $this->getContainer()->get('translator')->getLocale();

        // We build a fake request with the default locale
        $request = new Request();
        $request->setDefaultLocale($locale);

        $this->getContainer()->get('request_stack')->push($request);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time = $this->getContainer()->get('craue_config')->get('seta.notifications.days_before_renovation');
        $on = new \DateTime($time.' days midnight');
        $output->write('Enviando mensajes de renovación... ');
        $total = $this->sendReminderEmail(Message::RENEW_WARNING_MESSAGE, $on);
        $output->writeln("Hecho: [${total}].");

        $on = new \DateTime('yesterday midnight');
        $output->write('Enviando mensajes de caducidad... ');
        $total = $this->sendReminderEmail(Message::PENALTY_WARNING_MESSAGE, $on);
        $output->writeln("Hecho: [${total}].");

        $time = $this->getContainer()->get('craue_config')->get('seta.notifications.days_before_suspension');
        $on = new \DateTime('-'.$time.'days midnight');
        $output->write('Enviando mensajes de suspensión... ');
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

    private function checkPendingPenalties()
    {
        $penalties = $this->getContainer()->get('seta.repository.time_penalty')->findExpiredPenalties();
        /** @var Penalty $penalty */
        foreach ($penalties as $penalty) {
            $this->getContainer()->get('seta.service.close_penalty')->closePenalty($penalty);
        }
    }
}
