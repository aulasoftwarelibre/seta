<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Craue\ConfigBundle\CraueConfigBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new JavierEguiluz\Bundle\EasyAdminBundle\EasyAdminBundle(),
            new Hautzi\SystemMailBundle\HautziSystemMailBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sgomez\Bundle\SSPGuardBundle\SSPGuardBundle(),
            new Sgomez\DebugSwiftMailerBundle\SgomezDebugSwiftMailerBundle(),

            new Seta\CoreBundle\SetaCoreBundle(),
            new Seta\LockerBundle\SetaLockerBundle(),
            new Seta\MailerBundle\SetaMailerBundle(),
            new Seta\PenaltyBundle\SetaPenaltyBundle(),
            new Seta\RentalBundle\SetaRentalBundle(),
            new Seta\ResourceBundle\SetaResourceBundle(),
            new Seta\UserBundle\SetaUserBundle(),
            new Sgomez\BsDatetimepickerBundle\SgomezBsDatetimepickerBundle(),
            new Uco\Bundle\UserBundle\UcoUserBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Knp\Rad\FixturesLoad\Bundle\FixturesLoadBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
