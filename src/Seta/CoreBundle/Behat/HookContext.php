<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29/12/15
 * Time: 19:53.
 */
namespace Seta\CoreBundle\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Craue\ConfigBundle\Entity\Setting;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class HookContext.
 *
 * @codeCoverageIgnore
 */
class HookContext implements Context, KernelAwareContext
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase(BeforeScenarioScope $scope)
    {
        $this->getContainer()->get('knp_rad_fixtures_load.reset_schema_processor')->resetDoctrineSchema();
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $settings = [
            'seta.penalty.amount' => '2.0',
            'seta.notifications.days_before_renovation' => '2',
            'seta.notifications.days_before_suspension' => '8',
            'seta.duration.days_length_rental' => '7',
        ];

        foreach ($settings as $key => $value) {
            $setting = new Setting();
            $setting->setName($key);
            $setting->setValue($value);
            $em->persist($setting);
        }

        $em->flush();
    }

    /**
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }
}
