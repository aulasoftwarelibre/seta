<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Seta\CoreBundle\Command;

use Craue\ConfigBundle\Entity\Setting;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('craue:setting:remove')
            ->setDescription('Remove a config setting')
            ->addArgument('name', InputArgument::REQUIRED, 'Argument name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $setting = $em->getRepository('CraueConfigBundle:Setting')->findOneBy([
            'name' => $input->getArgument('name'),
        ]);

        if (!$setting) {
            throw new InvalidArgumentException('Argument doesn\'t exists.');
        }

        $em->remove($setting);
        $em->flush();

        $output->writeln('Removed.');
    }
}
