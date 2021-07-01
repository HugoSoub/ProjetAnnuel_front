<?php

namespace App\Controller;

use App\Entity\Certification;
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
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'formations');

        $formations = $errors = [];
        if ($response->getStatusCode() == 200) {
            $formations = $response->toArray();
        }

        return $this->render('formation/list.html.twig', [ 'formations' => $formations, 'errors' => $errors ]);
    }

    /**
     * @Route("/formation/add", name="formation_add")
     */
    public function addFormation(Request $request)
    {
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'certifications');

        $certifications = [];
        if ($response->getStatusCode() == 200) {
            foreach ($response->toArray() as $object) {
                $certification = new Certification();
                $certification->setId($object['id'])
                              ->setName($object['name'])
                              ->setDescription($object['description']);
                $certifications[] = $certification;
            }
        }

        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation, ['certifications' => $certifications]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->client->request('POST', $this->getParameter('api_url') . 'formations', [
                'json' => [
                    'name' => $formation->getName(),
                    'id_certification' => $formation->getCertification()->getId()
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                return $this->redirectToRoute('formation');
            }
        }

        return $this->render('formation/add.html.twig', [ 'form' => $form->createView() ]);
    }

    /**
     * @Route("/formation/{id}/remove", name="formation_remove")
     */
    public function removeFormation($id)
    {
        $response = $this->client->request('DELETE', $this->getParameter('api_url') . 'formations/' . $id);

        return $this->redirectToRoute('formation');
    }

    // /**
    //  * @Route("/formation/add", name="formation_add", options={"expose"=true}, methods={"POST"})
    //  */
    // public function addEvent(Request $request)
    // {
    //     $formation = new Formation();
    //     $form = $this->createForm(FormationType::class, $formation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $response = $this->client->request('POST', $this->getParameter('api_url') . 'formations', [
    //             'json' => ['name' => $formation->getName()]
    //         ]);
    //         if ($response->getStatusCode() == 200) {
    //             return new JsonResponse($formation, 200);
    //         }
    //     }

    //     return $this->render('calendar/event.add.html.twig', [ 'form' => $form->createView() ]);
    // }
}
