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

    public function __construct($host, $port)
    {
        $this->client = new Neo4jClient(new Transport($host, $port));
    }

    /**
     * @return \Everyman\Neo4j\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}