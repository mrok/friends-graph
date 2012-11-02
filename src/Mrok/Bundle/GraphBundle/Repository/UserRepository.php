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
        $queryString = 'START users=node:Users("type:user") RETURN users';
        $query = new Query($this->noe4jClient, $queryString);
        $result = $query->getResultSet();

        return $this->queryAndExtractUserData($queryString, 'users');
    }

    /**
     * Return nodes connected directly to specific user - show user friends
     * @param $userId - int
     *
     * @return array
     */
    public function getConnectedUsers($userId)
    {
        $queryString = 'START person=node:Users(user_id="' .$userId .'")
                        MATCH person-[KNOWS]-friends
                        RETURN friends';
        $query = new Query($this->noe4jClient, $queryString);
        $result = $query->getResultSet();

        $out = array();
        foreach ($result as $node) {
            $out[] = $node['friends']->getProperties();
        }

        return $out;
    }

    /**
     * Return users who are two steps away from the chosen user but not directly connected
     * @param $userId - int
     *
     * @return array
     */
    public function getFriendsOfFriends($userId)
    {
        $queryString = 'START person=node:Users(user_id="' .$userId .'")
                        MATCH person-[KNOWS*2]-fof
                        WHERE (person <> fof) AND not(person-[KNOWS*1]-fof)
                        RETURN DISTINCT fof';

        return $this->queryAndExtractUserData($queryString, 'fof');
    }

    /**
     * Return users in the group who know 2 or more direct friends of the chosen user,
     * but are not directly connected to the chosen user
     * @param $userId - int
     *
     * @return array
     */
    public function getSuggestedFriends($userId)
    {
        $queryString = 'START person=node:Users(user_id="' .$userId .'")
                        MATCH person-[KNOWS*2]-fof
                        WHERE (person <> fof) AND not(person-[KNOWS*1]-fof)
                        WITH fof, count(*) as commonf
                        WHERE commonf >= 2
                        RETURN  fof';

        return $this->queryAndExtractUserData($queryString, 'fof');
    }


    /**
     * Call Neo4j REST api with passed query,
     * If query success then user properties are extracted into array
     * @param string $query
     * @param string $userAlias
     *
     * @return array
     */
    private function queryAndExtractUserData($query, $userAlias)
    {
        $query = new Query($this->noe4jClient, $query);
        $result = $query->getResultSet();

        $out = array();
        foreach ($result as $node) {
            $out[] = $node[$userAlias]->getProperties();
        }

        return $out;
    }
}
