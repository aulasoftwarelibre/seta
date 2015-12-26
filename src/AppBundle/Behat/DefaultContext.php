<?php

namespace AppBundle\Behat;


use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class DefaultContext implements Context, KernelAwareContext
{
    /**
     * @var string
     */
    protected $applicationName = 'tuconsigna';

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    public function __construct($applicationName = null)
    {
        \Locale::setDefault('es');

        $this->faker = Factory::create();

        if (null !== $applicationName) {
            $this->applicationName = $applicationName;
        }
    }

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
        $this->getService('knp_rad_fixtures_load.reset_schema_processor')->resetDoctrineSchema();
    }

    /**
     * @param string $resourceName
     *
     * @return EntityRepository
     */
    protected function getRepository($resourceName)
    {
        return $this->getService($this->applicationName.'.repository.'.$resourceName);
    }

    /**
     * @return ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->getService('doctrine')->getManager();
    }

    /**
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function getService($id)
    {
        return $this->getContainer()->get($id);
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