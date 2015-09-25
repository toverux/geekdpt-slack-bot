<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;

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
        ]);
    }
}
