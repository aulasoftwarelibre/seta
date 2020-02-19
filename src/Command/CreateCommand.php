<?php

namespace App\Command;

use Craue\ConfigBundle\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2016 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('craue:setting:create')
            ->setDescription('Create a new config setting')
            ->addArgument('name', InputArgument::REQUIRED, 'Argument name')
            ->addArgument('section', InputArgument::OPTIONAL, 'Sections argument')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $setting = $em->getRepository('CraueConfigBundle:Setting')->findOneBy([
           'name' => $input->getArgument('name'),
        ]);

        if ($setting) {
            throw new InvalidArgumentException('Argument already exists.');
        }

        $setting = new Setting();
        $setting->setName($input->getArgument('name'));
        $setting->setSection($input->getArgument('section'));

        $em->persist($setting);
        $em->flush();

        $output->writeln('Created.');
    }
}
