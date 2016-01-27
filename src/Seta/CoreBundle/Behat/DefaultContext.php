<?php

namespace Seta\CoreBundle\Behat;


use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Result\StepResult;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class DefaultContext
 * @package Seta\CoreBundle\Behat
 * @codeCoverageIgnore
 */
class DefaultContext implements Context, KernelAwareContext
{
    /**
     * @var string
     */
    protected $applicationName = 'seta';

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
     * @return object The associated service
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

    /**
     * Dispatch an event
     *
     * @param $eventName
     * @param Event $event
     *
     * @return Event
     */
    protected function dispatch($eventName, Event $event)
    {
        return $this->getContainer()->get('event_dispatcher')->dispatch($eventName, $event);
    }
}
