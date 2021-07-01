<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CertificationController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    /**
     * @Route("/certification", name="certification")
     */
    public function list(): Response
    {
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'todos');

        $certifications = $errors = [];
        if ($response->getStatusCode() == 200) {
            $certifications = $response->toArray();
        }

        return $this->render('certification/list.html.twig', [ 'certifications' => $certifications, 'errors' => $errors ]);
    }
}
