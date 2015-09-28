<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FortuneCommand extends ContainerAwareCommand implements FancyCommandInterface
{
    protected function configure()
    {
        $this
            ->setName('gk:fortune')
            ->setDescription('Ragots, citations et voyance.')
            ->addOption('--text', null, InputOption::VALUE_OPTIONAL, 'Enter a text manually');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $randomcow = '/usr/games/cowsay -f $(ls /usr/share/cowsay/cows/ | shuf -n1)';

        if($text = $input->getOption('text')) {
            $text = escapeshellarg($text);
            $output->write(`echo $text | $randomcow`);
        } else {
            $output->write(`/usr/games/fortune | $randomcow`);
        }
    }

    public function getFancyStyle()
    {
        return [
            'name'   => 'Fortune',
            'avatar' => 'http://i.imgur.com/YpVWzJS.png'
        ];
    }
}
