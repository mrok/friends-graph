<?php

namespace Mrok\Bundle\GraphBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Everyman\Neo4j\Node;
use Everyman\Neo4j\Relationship;

class Neo4jPopulateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('neo4j:load-data')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var \Everyman\Neo4j\Client
         */
        $client = $translator = $this->getContainer()->get('mrok_graph_bundle.neo4j.client')->getClient();

        $dataToPersist = require __DIR__ . '/../Fixtures/data.php';
        $nodeList = array();

        ##add nodes
        foreach ($dataToPersist as $person) {
            $node = new Node($client);

            unset($person['friends']);
            $node->setProperties($person)->save();

            $nodeList[$person['id']] = $node;
            $output->writeln($person['firstName'] . ' ' . $person['surname'] . ' added to graph');
        }

        foreach ($dataToPersist as $person) {
            $node = $nodeList[$person['id']];
            foreach ($person['friends'] as $friendId) {
                $node->relateTo($nodeList[$friendId], 'know')->save();

                $output->writeln($node->getProperty('firstName') . ' knows ' . $nodeList[$friendId]->getProperty('firstName'));
            }
        }

        $output->writeln('Finish');
    }
}

