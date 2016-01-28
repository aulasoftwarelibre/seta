<?php

namespace spec\Seta\CoreBundle\Command;

use Craue\ConfigBundle\Util\Config;
use Seta\LockerBundle\Entity\Locker;
use Seta\MailerBundle\Business\MailService;
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
        Locker $locker,
        Rental $rental,
        User $user
    ) {
        // $this->beConstructedWith();
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
        ContainerInterface $container,
        Config $config,
        InputInterface $input,
        MailService $mailer,
        OutputInterface $output,
        Rental $rental,
        RentalRepository $rentalRepository,
        RequestStack $requestStack,
        TranslatorInterface $translator
    ) {
        $container->get('craue_config')->shouldBeCalled()->willReturn($config);
        $config->get('seta.notifications.days_before_renovation')->shouldBeCalled()->willReturn('2');
        $config->get('seta.notifications.days_before_suspension')->shouldBeCalled()->willReturn('8');
        $container->get('seta.repository.rental')->shouldBeCalled()->willReturn($rentalRepository);
        $container->get('seta_mailing')->shouldBeCalled()->willReturn($mailer);
        $container->get('translator')->shouldBeCalled()->willReturn($translator);
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
