<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Entity\Formation;
use App\Entity\Session;
use App\Form\SessionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    /**
     * @Route("/session", name="session")
     */
    public function list(): Response
    {
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'sessions');

        $sessions = $errors = [];
        if ($response->getStatusCode() == 200) {
            $sessions = $response->toArray();
        }

        return $this->render('session/list.html.twig', [ 'sessions' => $sessions, 'errors' => $errors ]);
    }

    /**
     * @Route("/session/add", name="session_add")
     */
    public function addSession(Request $request)
    {
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'formations');

        $formations = [];
        if ($response->getStatusCode() == 200) {
            foreach ($response->toArray() as $object) {
                $formation = new Formation();
                $formation->setId($object['id'])
                          ->setName($object['name']);
                $formations[] = $formation;
            }
        }

        $session = new Session();
        $form = $this->createForm(SessionType::class, $session, ['formations' => $formations]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->client->request('POST', $this->getParameter('api_url') . 'sessions', [
                'json' => [
                    'name' => $session->getName(),
                    'status' => 'En cours',
                    'id_formation' => $session->getFormation()->getId()
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                return $this->redirectToRoute('session');
            }
        }

        return $this->render('session/add.html.twig', [ 'form' => $form->createView() ]);
    }

    /**
     * @Route("/session/{id}/remove", name="session_remove")
     */
    public function removeSession($id)
    {
        $response = $this->client->request('DELETE', $this->getParameter('api_url') . 'sessions/' . $id);

        return $this->redirectToRoute('session');
    }

    /**
     * @Route("/session/formation/edit/{id}", name="session_formation_edit", options={"expose"=true}, methods={"PUT"})
     */
    public function editSessionFormation(Request $request, $id)
    {
        $session_formations = $this->client->request('GET', $this->getParameter('api_url') . 'session_formations/' . $id)->toArray();

        $response = $this->client->request('PUT', $this->getParameter('api_url') . 'session_formations/' . $id, [
            'json' => array_merge($session_formations[0], $request->request->all())
        ]);

        return new JsonResponse('', 200);
    }

    /**
     * @Route("/session/{session_id}/user/{user_id}/add/", name="session_user_add", options={"expose"=true}, methods={"POST"})
     */
    public function addUserSession($session_id, $user_id)
    {
        $response = $this->client->request('POST', $this->getParameter('api_url') . 'user_sessions', [
            'json' => [
                'id_user' => $user_id,
                'id_session' => $session_id
            ]
        ]);

        return new JsonResponse('', 200);
    }
}
