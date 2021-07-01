<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterviewController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    /**
     * @Route("/interview", name="interview")
     */
    public function list(): Response
    {
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'todos');

        $interviews = $errors = [];
        if ($response->getStatusCode() == 200) {
            $interviews = $response->toArray();
        }

        return $this->render('interview/list.html.twig', [ 'interviews' => $interviews, 'errors' => $errors ]);
    }
}
