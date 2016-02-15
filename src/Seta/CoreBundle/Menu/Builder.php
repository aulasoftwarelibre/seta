<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Seta\CoreBundle\Menu;


use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'ui large secondary inverted pointing menu');

        $menu->addChild('Inicio', ['route' => 'homepage'])->setExtra('icon', 'home');

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $menu->addChild('Histórico', ['route' => 'history'])->setExtra('icon', 'history');
        }

        return $menu;
    }

    public function followingMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Inicio', ['route' => 'homepage'])->setExtra('icon', 'home');

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $menu->addChild('Histórico', ['route' => 'history'])->setExtra('icon', 'history');
        }


        return $menu;
    }
}