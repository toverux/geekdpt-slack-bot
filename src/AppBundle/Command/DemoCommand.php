<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DemoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('gk:demo')
            ->setDescription('Saluer une personne')
            ->addArgument('name', InputArgument::OPTIONAL, 'Qui voulez vous saluer??')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if ($name) {
            $text = 'Bonjour '.$name;
        } else {
            $text = 'Bonjour';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }
}
