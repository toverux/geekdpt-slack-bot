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
            new InputOption('--broadcast', '-b', InputOption::VALUE_NONE, 'Broadcast to current channel'),
            new InputOption('--no-markdown', null, InputOption::VALUE_NONE, 'Disable markdown on simple outputs'),
        ]);
    }
}
