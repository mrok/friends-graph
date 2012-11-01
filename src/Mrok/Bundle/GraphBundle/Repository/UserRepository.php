<?php

namespace Mrok\Bundle\GraphBundle\Repository;

use Mrok\Bundle\GraphBundle\Repository\BaseRepository;
use Everyman\Neo4j\Cypher\Query;

class UserRepository extends BaseRepository
{

    /**
     * Return all users from Users index
     *
     * @return array
     */
    public function getAllUsers()
    {
        $queryString = 'START n=node:Users("type:user") RETURN n';
        $query = new Query($this->noe4jClient, $queryString);
        $result = $query->getResultSet();

        $out = array();
        foreach ($result as $node) {
            $out[] = $node['n']->getProperties();
        }

        return $out;
    }

    /**
     * Return nodes connected directly to specific user - show user friends
     * @param $userId - int
     *
     * @return array
     */
    public function getConnectedUsers($userId)
    {
        $queryString = 'START person=node(' . $userId . ')
                        MATCH person-[KNOWS]-friend
                        RETURN friend';
        $query = new Query($this->noe4jClient, $queryString);
        $result = $query->getResultSet();

        $out = array();
        foreach ($result as $node) {
            $out[] = $node['friend']->getProperties();
        }

        return $out;
    }

    /**
     * Return nodes connected directly to specific user - show user friends
     * @param $userId - int
     *
     * @return array
     */
    public function getFriendsOfFriends($userId)
    {
        $queryString = 'START person=node(' . $userId . ')
                        MATCH person-[KNOWS*2]-fof
                        WHERE (person <> fof) AND not(person-[KNOWS*1]-fof)
                        RETURN DISTINCT fof';

        $query = new Query($this->noe4jClient, $queryString);
        $result = $query->getResultSet();

        $out = array();
        foreach ($result as $node) {
            $out[] = $node['friend']->getProperties();
        }

        return $out;
    }

}
