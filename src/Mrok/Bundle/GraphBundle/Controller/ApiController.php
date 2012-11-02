<?php

namespace Mrok\Bundle\GraphBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;

/**
 * @Route("/api")
 */
class ApiController extends FOSRestController
{

    /**
     * @Route("/users", name="api_get_all")
     * @Method("GET")
     */
    public function indexAction()
    {

        $repository = $this->get('mrok_graph_bundle.neo4j.client')->getRepository('User');
        $data = $repository->getAllUsers();

        $view = $this->view($data, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Route("/connected/{userId}", name="api_get_connected")
     * @Method("GET")
     */
    public function connectedUserAction($userId)
    {
        $repository = $this->get('mrok_graph_bundle.neo4j.client')->getRepository('User');
        $data = $repository->getConnectedUsers($userId);

        $view = $this->view($data, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Route("/friends-of-friends/{userId}", name="api_get_friends_of_friends")
     * @Method("GET")
     */
    public function friendsOfFriendsAction($userId)
    {
        $repository = $this->get('mrok_graph_bundle.neo4j.client')->getRepository('User');
        $data = $repository->getFriendsOfFriends($userId);

        $view = $this->view($data, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Route("/suggested-friends/{userId}", name="api_get_suggested_friends")
     * @Method("GET")
     */
    public function suggestedFriendsAction($userId)
    {
        $repository = $this->get('mrok_graph_bundle.neo4j.client')->getRepository('User');
        $data = $repository->getSuggestedFriends($userId);

        $view = $this->view($data, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

}
