<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CoreController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $sessions = $this->client->request('GET', $this->getParameter('api_url') . 'sessions/status/' . "En cours")->toArray();

        foreach ($sessions as $key => $session) {
            $users = $this->client->request('GET', $this->getParameter('api_url') . 'user_sessions/session/' . $session['id'])->toArray();
            $session_formations = $this->client->request('GET', $this->getParameter('api_url') . 'session_formations/session/' . $session['id'])->toArray();

            foreach ($session_formations as $key2 => $session_formation) {
                $session_formations[$key2]['id_formation'] = $this->client->request('GET', $this->getParameter('api_url') . 'formations/' . $session_formation['id_formation'])->toArray();
            }
            $sessions[$key] = array_merge($session, ['formations' => $session_formations, 'users' => $users]);
        }

        return $this->render('core/index.html.twig', ['sessions' => $sessions]);
    }
}
