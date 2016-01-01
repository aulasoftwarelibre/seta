<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 30/12/15
 * Time: 17:22
 */

namespace Seta\MailerBundle\Behat;


use Alex\MailCatcher\Behat\MailCatcherContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class MailerContext
 * @package Seta\MailerBundle\Behat
 * @codeCoverageIgnore
 */
class MailerContext extends MailCatcherContext implements KernelAwareContext
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @When /^se envía un correo de aviso de renovación con (\d+) días de antelación$/
     */
    public function seEnvíaUnCorreoDeAvisoDeRenovaciónConDíasDeAntelación($dias)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
           'command' => 'seta:cron:run'
        ]);

        $output = new NullOutput();
        $application->run($input, $output);
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}