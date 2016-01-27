<?php

namespace Seta\CoreBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('seta_core');

        /*
         seta_core:
            penalty:
                amount: 2.0
            notification:
                days_before_renovation: 2
                days_before_suspension: 8
            duration:
                days_length_rental: 7
         */

        $rootNode
            ->children()
                ->arrayNode('penalty')
                    ->children()
                        ->floatNode('amount')->defaultValue('2.0')->end()
                    ->end()
                ->end() // penalty
                ->arrayNode('notification')
                    ->children()
                        ->scalarNode('days_before_renovation')->defaultValue('2')->end()
                        ->scalarNode('days_before_suspension')->defaultValue('8')->end()
                    ->end()
                ->end() // notification
                ->arrayNode('duration')
                    ->children()
                        ->scalarNode('days_length_rental')->defaultValue('7')->end()
                    ->end()
                ->end() // duration
            ->end()
        ;

        return $treeBuilder;
    }
}
