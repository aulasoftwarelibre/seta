<?php
/*
 * This file is part of the SimpleSamlBundle.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sgomez\Bundle\SimpleSamlBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
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
        $rootNode = $treeBuilder->root('simple_saml');

        $rootNode
            ->children()
                ->scalarNode('authsource')
                    ->defaultValue('default-sp')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('path')
                    ->defaultValue('/var/simplesamlphp')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('userid_attribute')
                    ->defaultValue('eduPersonPrincipalName')
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
