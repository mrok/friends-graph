<?php

namespace Mrok\Bundle\GraphBundle\Service;

use Everyman\Neo4j\Client as Neo4jClient;
use Everyman\Neo4j\Transport\Curl as Transport;

class Client
{
    /**
     * @var \Everyman\Neo4j\Client
     */
    protected $client;

    /**
     * @param string $host
     * @param int $port
     * @param string|null $username
     * @param string|null $password
     */
    public function __construct($host, $port, $username, $password)
    {
        $transport = new Transport($host, $port);
        $transport->setAuth($username, $password);

        $this->client = new Neo4jClient($transport);
    }

    /**
     * @return \Everyman\Neo4j\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}