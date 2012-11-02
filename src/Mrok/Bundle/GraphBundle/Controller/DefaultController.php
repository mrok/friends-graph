<?php

namespace Mrok\Bundle\GraphBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Everyman\Neo4j\Cypher\Query;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/friends/direct", name="direct_friends")
     * @Template("MrokGraphBundle:Default:friends.html.twig")
     */
    public function directFriendsAction()
    {
        return array('pageHeader' => 'Direct Friends', 'startAction'=> 'friends/direct');
    }

    /**
     * @Route("/friends/friends", name="friends_of_friends")
     * @Template("MrokGraphBundle:Default:friends.html.twig")
     */
    public function friendsOfFriendsAction()
    {
        return array('pageHeader' => 'Friends of Friends', 'startAction'=> 'friends/friends');
    }

    /**
     * @Route("/friends/suggested", name="suggested_friends")
     * @Template("MrokGraphBundle:Default:friends.html.twig")
     */
    public function suggestedFriendsAction()
    {
        return array('pageHeader' => 'Suggested friends', 'startAction'=> 'friends/suggested');
    }
}
