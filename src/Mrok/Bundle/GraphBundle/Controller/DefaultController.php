<?php

namespace Mrok\Bundle\GraphBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $name = 'neo4j test';
        return array('name' => $name);
    }

    /**
     * @Route("/friends/direct", name="direct_friends")
     * @Template("MrokGraphBundle:Default:direct-friends.html.twig")
     */
    public function directFriendsAction()
    {
        return array();
    }

    /**
     * @Route("/friends/friends", name="friends_of_friends")
     * @Template("MrokGraphBundle:Default:direct-friends.html.twig")
     */
    public function frinedsOfFriendsAction()
    {
        return array();
    }

    /**
     * @Route("/friends/suggested", name="suggested_friends")
     * @Template("MrokGraphBundle:Default:direct-friends.html.twig")
     */
    public function suggestedFriendsAction()
    {
        return array();
    }
}
