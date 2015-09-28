<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ManCommand extends RADCommand implements FancyCommandInterface
{
    use FancyCommandTrait;

    public function getAvatar()
    {
        return [
            'name'  => 'RTFM',
            'image' => 'http://i.imgur.com/XCFli2p.png',
        ];
    }

    protected function configure()
    {
        $this
            ->setName('gk:man')
            ->setDescription('Le manuel du Geek Dpt')
            ->addArgument('manual-entry', InputArgument::OPTIONAL, 'Le nom de la page de manuel')
            ->addOption('--list', '-l', InputOption::VALUE_NONE, 'Lister les pages de manuel');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($input->getOption('list')) {
            $this->displayEntriesList($output);
        } else {
            $this->displayEntry($output, $input->getArgument('manual-entry'));
        }
    }

    private function displayEntriesList(OutputInterface $output)
    {
        $entries = $this->getDEMRepository('AppBundle:ManEntry')->findAll();

        foreach($entries as $entry) {
            $output->writeln($entry->getName());
        }
    }

    private function displayEntry(OutputInterface $output, $entryName)
    {
        $Entries = $this->getDEMRepository('AppBundle:ManEntry');

        if(!$entry = $Entries->findOneByName($entryName)) {
            throw new \InvalidArgumentException("No manual entry named '{$entryName}'.");
        }

        $this->outputAsCode = false;
        $output->write($entry->getContent());
    }
}
