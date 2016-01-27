<?php

namespace Seta\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 * @codeCoverageIgnore
 */
class SetaCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('seta.penalty.amount', $config['penalty']['amount']);
        $container->setParameter('seta.notifications.days_before_renovation', $config['notification']['days_before_renovation']);
        $container->setParameter('seta.notifications.days_before_suspension', $config['notification']['days_before_suspension']);
        $container->setParameter('seta.duration.days_length_rental', $config['duration']['days_length_rental']);
    }
}
