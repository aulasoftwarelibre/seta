<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sgomez\BsDatetimepickerBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $engines = $container->getParameter('templating.engines');

        if (in_array('twig', $engines)) {
            $container->setParameter(
                'twig.form.resources',
                array_merge(
                    array('SgomezBsDatetimepickerBundle:Form:bsdatetime_widget.html.twig'),
                    $container->getParameter('twig.form.resources')
                )
            );
        }
    }
}