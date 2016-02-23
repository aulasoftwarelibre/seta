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

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class SimpleSamlExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('simple_saml.authsource', $config['authsource']);
        $autoloadPath = sprintf('%s/lib/_autoload.php', rtrim($config['path'], '/'));

        if (false === file_exists($autoloadPath)) {
            throw new InvalidConfigurationException('The path "simple_saml.path" doesn\'t contain a valid SimpleSAMLphp installation.');
        }

        $container->setParameter('simple_saml.autoload_path', $autoloadPath);
        $container->setParameter('simple_saml.userid_attribute', $config['userid_attribute']);
    }
}
