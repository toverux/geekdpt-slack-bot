<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Application;

/**
 * {@inheritDoc}
 */
class CustomApplication extends Application
{
    /**
     * {@inheritDoc}
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'La commande a exécuter'),
            new InputOption('--share', '-s', InputOption::VALUE_NONE, 'Share the command output to the current channel'),
            new InputOption('--no-markdown', null, InputOption::VALUE_NONE, 'Disable markdown on simple outputs'),
        ]);
    }
}
