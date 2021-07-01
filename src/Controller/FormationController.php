<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    /**
     * @Route("/formation", name="formation")
     */
    public function list(): Response
    {
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'todos');

        $formations = $errors = [];
        if ($response->getStatusCode() == 200) {
            $formations = $response->toArray();
        }

        return $this->render('formation/list.html.twig', [ 'formations' => $formations, 'errors' => $errors ]);
    }

    /**
     * @Route("/formation/add", name="formation_add", options={"expose"=true}, methods={"POST"})
     */
    public function addEvent(Request $request)
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $response = $this->client->request('POST', $this->getParameter('api_url') . 'todos', [
    
            // ]);
            return new JsonResponse($formation, 200);
        }

        return $this->render('calendar/event.add.html.twig', [ 'form' => $form->createView() ]);
    }
}
