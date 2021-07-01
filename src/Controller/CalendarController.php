<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
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
}
