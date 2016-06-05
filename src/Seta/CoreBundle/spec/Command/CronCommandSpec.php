<?php

namespace spec\Seta\CoreBundle\Command;

use Craue\ConfigBundle\Util\Config;
use Seta\LockerBundle\Entity\Locker;
use Seta\MailerBundle\Business\MailService;
use Seta\PenaltyBundle\Business\ClosePenaltyService;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\PenaltyBundle\Repository\TimePenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Repository\RentalRepository;
use Seta\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

class CronCommandSpec extends ObjectBehavior
{
    public function let(
        ContainerInterface $container,
        Config $config,
        Locker $locker,
        Rental $rental,
        RentalRepository $rentalRepository,
        TimePenaltyRepository $timePenaltyRepository,
        User $user
    ) {
        $container->get('craue_config')->willReturn($config);
        $container->get('seta.repository.time_penalty')->willReturn($timePenaltyRepository);
        $container->get('seta.repository.rental')->willReturn($rentalRepository);

        $rental->getUser()->willReturn($user);
        $rental->getLocker()->willReturn($locker);
        $rental->getIsRenewable()->willReturn(true);

        $locker->getCode()->willReturn('100');

        $user->getEmail()->willReturn('client@gmail.com');
        $user->getUsername()->willReturn('client@gmail.com');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\CoreBundle\Command\CronCommand');
    }

    public function it_is_a_container_aware_command()
    {
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('seta:cron:run');
    }

    public function it_send_emails(
        ClosePenaltyService $closePenaltyService,
        ContainerInterface $container,
        Config $config,
        InputInterface $input,
        MailService $mailer,
        OutputInterface $output,
        Penalty $penalty,
        Rental $rental,
        RentalRepository $rentalRepository,
        RequestStack $requestStack,
        TimePenaltyRepository $timePenaltyRepository,
        TranslatorInterface $translator
    ) {
        $config->get('seta.notifications.days_before_renovation')->shouldBeCalled()->willReturn('2');
        $config->get('seta.notifications.days_before_suspension')->shouldBeCalled()->willReturn('8');
        $container->get('seta_mailing')->shouldBeCalled()->willReturn($mailer);
        $container->get('translator')->shouldBeCalled()->willReturn($translator);
        $container->get('seta.service.close_penalty')->willReturn($closePenaltyService);
        $timePenaltyRepository->findExpiredPenalties()->shouldBeCalled()->willReturn([$penalty]);
        $closePenaltyService->closePenalty($penalty);

        $translator->getLocale()->shouldBeCalled()->willReturn('es');
        $container->get('request_stack')->shouldBeCalled()->willReturn($requestStack);
        $requestStack->push(Argument::type(Request::class))->shouldBeCalled();

        $rentalRepository
            ->getExpireOnDateRentals(Argument::type(\DateTime::class))
            ->willReturn([$rental])
            ->shouldBeCalled()
        ;
        $mailer->sendEmail($rental, Argument::any())->shouldBeCalled();

        $this->setContainer($container);
        $this->run($input, $output);
    }
}
