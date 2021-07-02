<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    /**
     * @Route("/calendar/event/add/{type}", name="calendar_event_add", options={"expose"=true}, methods={"GET"})
     */
    public function addEvent($type)
    {
        if ($type == "formation") {
            $formation = new Formation();
            $form = $this->createForm(FormationType::class, $formation);
        }
        return $this->render('calendar/event.add.html.twig', [ 'form' => $form->createView() ]);
    }

     /**
     * @Route("/calendar", name="calendar", options={"expose"=true}, methods={"GET"})
     */
    public function index()
    {
        $session_formations = $this->client->request('GET', $this->getParameter('api_url') . 'session_formations/')->toArray();

        $result = [];
        foreach ($session_formations as $session_formation) {
            $formation = $this->client->request('GET', $this->getParameter('api_url') . 'formations/' . $session_formation['id_formation'])->toArray();
            $session = $this->client->request('GET', $this->getParameter('api_url') . 'sessions/' . $session_formation['id_session'])->toArray();
            $users = $this->client->request('GET', $this->getParameter('api_url') . 'user_sessions/session/' . $session[0]['id'])->toArray();

            $result[] = [
                'id' => $session_formation['id'],
                'title' => $session[0]['name'] . ' <br />' . count($users) . ' / 30 candidat(s)' . ' <br /><br /> ' . $formation[0]['name'],
                'start' => date('Y-m-d', strtotime($session_formation['date'] . ' +1 day')),
                'borderColor' => '#4680ff',
                'backgroundColor' => '#4680ff',
                'textColor' => '#fff',
                'allDay' => true
            ];
        }

        return new JsonResponse($result, 200);
    }
}
