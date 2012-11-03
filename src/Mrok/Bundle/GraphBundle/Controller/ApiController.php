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
    public function allUsersAction()
    {
        $fun = function($repository)
        {
            return $repository->getAllUsers();
        };

        return $this->obtainUserData($fun);
    }

    /**
     * @Route("/connected/{userId}", name="api_get_connected")
     * @Method("GET")
     */
    public function directFriendsAction($userId)
    {
        $userId = intval($userId);
        $fun = function($repository) use ($userId)
        {
            return $repository->getConnectedUsers($userId);
        };

        return $this->obtainUserData($fun);
    }

    /**
     * @Route("/friends-of-friends/{userId}", name="api_get_friends_of_friends")
     * @Method("GET")
     */
    public function friendsOfFriendsAction($userId)
    {
        $userId = intval($userId);
        $fun = function($repository) use ($userId)
        {
            return $repository->getFriendsOfFriends($userId);
        };

        return $this->obtainUserData($fun);
    }

    /**
     * @Route("/suggested-friends/{userId}", name="api_get_suggested_friends")
     * @Method("GET")
     */
    public function suggestedFriendsAction($userId)
    {
        $userId = intval($userId);
        $fun = function($repository) use ($userId)
        {
            return $repository->getSuggestedFriends($userId);
        };

        return $this->obtainUserData($fun);
    }

    /**
     *
     * @param \Closure $fun - function which accept repository as input parameter
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function obtainUserData(\Closure $fun)
    {
        $repository = $this->get('mrok_graph_bundle.neo4j.client')->getRepository('User');
        $data = $fun($repository);

        $view = $this->view($data, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

}
