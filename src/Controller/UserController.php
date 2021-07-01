<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    /**
     * @Route("/user", name="user")
     */
    public function list(): Response
    {
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'users');

        $users = $errors = [];
        if ($response->getStatusCode() == 200) {
            $users = $response->toArray();
        }

        return $this->render('user/list.html.twig', [ 'users' => $users, 'errors' => $errors ]);
    }
}
