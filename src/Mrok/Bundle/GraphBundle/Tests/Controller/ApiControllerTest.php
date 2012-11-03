<?php

namespace Mrok\Bundle\GraphBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{

    /**
     * All api request return json with array of users in response
     * This method extract
     * @param string $json
     *
     * @return array
     */
    public function extractUserIdsFromResponse($json)
    {
        $users = json_decode($json);
        $out = array();
        foreach ($users as $user) {
            $out[] = $user->user_id;
        }
        sort($out);
        return $out;
    }

    /**
     * @test
     */
    public function allUsers()
    {
        $client = static::createClient();

        $client->request('GET', '/api/users');
        $response = $client->getResponse();
        $userIds = $this->extractUserIdsFromResponse($response->getContent());

        $this->assertCount(20, $userIds);
        $this->assertEquals(range(1, 20), $userIds);
    }

    /**
     * @test
     * @dataProvider directFriendsDataProvider
     */
    public function directFriends($userId, $expectedUserIds)
    {
        $client = static::createClient();

        $client->request('GET', '/api/connected/' . $userId);
        $response = $client->getResponse();
        $userIds = $this->extractUserIdsFromResponse($response->getContent());
        $this->assertEquals($expectedUserIds, $userIds);
    }

    /**
     * @test
     * @dataProvider friendsOfFriendsDataProvider
     */
    public function friendsOfFriends($userId, $expectedUserIds)
    {
        $client = static::createClient();

        $client->request('GET', '/api/friends-of-friends/' . $userId);
        $response = $client->getResponse();
        $userIds = $this->extractUserIdsFromResponse($response->getContent());
        $this->assertEquals($expectedUserIds, $userIds);
    }

    /**
     * @test
     * @dataProvider suggestedFriendsProvider
     */
    public function suggestedFriends($userId, $expectedUserIds)
    {
        $client = static::createClient();

        $client->request('GET', '/api/suggested-friends/' . $userId);
        $response = $client->getResponse();
        $userIds = $this->extractUserIdsFromResponse($response->getContent());
        $this->assertEquals($expectedUserIds, $userIds);
    }

    public static function directFriendsDataProvider()
    {
        $test1 = array(1, array(2));
        $test2 = array(20, array(7, 11, 12, 13, 16, 17, 19));
        $test3 = array(14, array(13, 15));

        return array($test1, $test2, $test3);
    }

    public static function friendsOfFriendsDataProvider()
    {
        $test1 = array(1, array(3));
        $test2 = array(20, array(3, 5, 8, 9, 10, 14, 18));
        $test3 = array(14, array(12, 20));

        return array($test1, $test2, $test3);
    }

    public static function suggestedFriendsProvider()
    {
        $test1 = array(1, array());
        $test2 = array(20, array(5, 18));
        $test3 = array(14, array());

        return array($test1, $test2, $test3);
    }
}
