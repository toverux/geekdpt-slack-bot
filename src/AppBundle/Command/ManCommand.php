<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ManCommand extends ContainerAwareCommand implements FancyCommandInterface
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
        $entryName = $input->getArgument('manual-entry');

        if($input->getOption('list') || !$entryName) {
            $this->displayEntriesList($output);
        } else {
            $this->displayEntry($output, $entryName);
        }
    }

    private function displayEntriesList(OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $entries = $em->getRepository('AppBundle:ManEntry')->findAll();

        foreach($entries as $entry) {
            $output->writeln($entry->getName());
        }
    }

    private function displayEntry(OutputInterface $output, $entryName)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        if(!$entry = $em->getRepository('AppBundle:ManEntry')->findOneByName($entryName)) {
            throw new \InvalidArgumentException("No manual entry named '{$entryName}'.");
        }

        $this->outputAsCode = false;
        $output->write($entry->getContent());
    }
}
