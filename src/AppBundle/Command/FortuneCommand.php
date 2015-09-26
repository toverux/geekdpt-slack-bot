<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FortuneCommand extends ContainerAwareCommand implements FancyCommandInterface
{
    protected function configure()
    {
        $this
            ->setName('gk:fortune')
            ->setDescription('Ragots, citations et voyance.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(`/usr/games/fortune|/usr/games/cowsay`);
    }

    public function getFancyStyle()
    {
        return [
            'name'   => 'Fortune',
            'avatar' => 'http://i.imgur.com/YpVWzJS.png'
        ];
    }
}
