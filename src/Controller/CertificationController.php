<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Form\CertificationType;
use Symfony\Component\HttpFoundation\Request;
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
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'certifications');

        $certifications = $errors = [];
        if ($response->getStatusCode() == 200) {
            $certifications = $response->toArray();
        }

        return $this->render('certification/list.html.twig', [ 'certifications' => $certifications, 'errors' => $errors ]);
    }

    /**
     * @Route("/certification/add", name="certification_add")
     */
    public function addCertification(Request $request)
    {
        $certification = new Certification();
        $form = $this->createForm(CertificationType::class, $certification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->client->request('POST', $this->getParameter('api_url') . 'certifications', [
                'json' => [
                    'name' => $certification->getName(),
                    'description' => $certification->getDescription()
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                return $this->redirectToRoute('certification');
            }
        }

        return $this->render('certification/add.html.twig', [ 'form' => $form->createView() ]);
    }
}
