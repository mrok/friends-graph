<?php

namespace Mrok\Bundle\GraphBundle\Repository;

use Everyman\Neo4j\Client;

abstract class BaseRepository
{

    protected $noe4jClient;

    /**
     * @param \Everyman\Neo4j\Client $client
     */
    public function setNeo4Client(Client $client) {
        $this->noe4jClient= $client;
    }

}
