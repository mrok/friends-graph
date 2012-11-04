<?php

namespace Mrok\Bundle\GraphBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Everyman\Neo4j\Node;
use Everyman\Neo4j\Index\NodeIndex;
use Everyman\Neo4j\Relationship;

class Neo4jPopulateCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    private $output = null;

    protected function configure()
    {
        $this
            ->setName('neo4j:load-data')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $loadedData = require __DIR__ . '/../Fixtures/data.php';
        $output->writeln('Nodes to import ' . count($loadedData));

        $savedNodes = $this->persistNodes($loadedData);
        $this->persistRelations($loadedData, $savedNodes);
        $this->createNodeIndex($savedNodes);

        $output->writeln('Finish');
    }

    /**
     * Persist nodes into db
     * @param array $nodes
     *
     * @return array of saved nodes, keys in array are user ids
     */
    private function persistNodes($nodes)
    {
        /**
         * @var \Everyman\Neo4j\Client
         */
        $client = $this->getContainer()->get('mrok_graph_bundle.neo4j.client')->getClient();
        $output = $this->output;
        $savedNodes = array();

        for ($i = 0, $count = count($nodes); $i < $count; $i++) {
            $person = $nodes[$i];

            $person['user_id'] = $person['id']; //follow the coding standards
            $person['first_name'] = $person['firstName']; //follow the coding standards
            $person['__type'] = 'user';
            unset($person['friends'], $person['id'], $person['firstName']);

            $node = new Node($client);
            $node->setProperties($person)->save();

            $savedNodes[$person['user_id']] = $node;
            $output->writeln('Node ' . ($i + 1) . ' ' . $person['first_name'] . ' ' . $person['surname'] . ' added to graph');
        }

        return $savedNodes;
    }

    /**
     * Save relationships in db
     * @param array $relations
     * @param array $nodes
     */
    private function persistRelations($relations, $nodes)
    {
        $output = $this->output;
        $output->writeln('Saving relations');
        $storedRelations = array();

        foreach ($relations as $person) {
            $uId = $person['id'];
            $user = $nodes[$uId];

            foreach ($person['friends'] as $fId) {
                $relationPattern = $uId . '-' . $fId;
                $relationPatternReverse = $fId . '-' . $uId;
                if (!in_array($relationPatternReverse, $storedRelations)) { //ony one way relations
                    $friend = $nodes[$fId];
                    $user->relateTo($friend, 'knows')->save();

                    $storedRelations[] = $relationPattern;
                    $output->writeln($user->getProperty('first_name') . ' knows ' . $friend->getProperty('first_name'));
                }
            }
        }
    }

    /**
     * Add nodes to index
     * @param array $nodes
     */
    private function createNodeIndex($nodes)
    {
        $this->output->writeln('Creating index');
        /**
         * @var \Everyman\Neo4j\Client
         */
        $client = $this->getContainer()->get('mrok_graph_bundle.neo4j.client')->getClient();

        $index = new NodeIndex($client, 'Users');
        $index->save();

        foreach ($nodes as $user) {
            $index->add($user, 'user_id', $user->getProperty('user_id'));
            $index->add($user, 'type', $user->getProperty('__type'));
        }
        $index->save();
    }
}