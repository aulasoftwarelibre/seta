<?php

namespace Ceeps\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ceeps_core');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->arrayNode('notifications')
                    ->children()
                        ->scalarNode('renovation')->defaultValue('1 week')->end()
                        ->scalarNode('reminder')->defaultValue('2 days')->end()
                        ->scalarNode('suspension')->defaultValue('1 week')->end()
                    ->end()
                ->end() // notifications
            ->end()
        ;

        return $treeBuilder;
    }
}
