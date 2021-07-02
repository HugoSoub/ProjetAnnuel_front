<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            foreach ($users as $key => $user) {
                $users[$key]['roles'] = json_decode($user['roles']);
            }
        }

        return $this->render('user/list.html.twig', [ 'users' => $users, 'errors' => $errors ]);
    }

    /**
     * @Route("/user/all", name="user_all", options={"expose"=true}, methods={"GET"})
     */
    public function getAllUsers()
    {
        $users = $this->client->request('GET', $this->getParameter('api_url') . 'users/roles/Candidat')->toArray();

        $result = [];
        foreach ($users as $user) {
            $result[$user['id']] = $user['name'] . ' ' . $user['firstname'];
        }

        return new JsonResponse($result, 200);
    }

    /**
     * @Route("/user/add", name="user_add")
     */
    public function addUser(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->client->request('POST', $this->getParameter('api_url') . 'users', [
                'json' => [
                    'name' => $user->getName(),
                    'firstname' => $user->getFirstname(),
                    'email' => $user->getEmail(),
                    'roles' => json_encode($user->getRoles())
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                return $this->redirectToRoute('user');
            }
        }

        return $this->render('user/add.html.twig', [ 'form' => $form->createView() ]);
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function editUser(Request $request, $id)
    {
        $response = $this->client->request('GET', $this->getParameter('api_url') . 'users/' . $id)->toArray()[0];

        $user = new User();
        $user->setId($response['id'])
             ->setName($response['name'])
             ->setFirstname($response['firstname'])
             ->setEmail($response['email'])
             ->setRoles(json_decode($response['roles']));

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->client->request('PUT', $this->getParameter('api_url') . 'users/' . $id, [
                'json' => [
                    'name' => $user->getName(),
                    'firstname' => $user->getFirstname(),
                    'email' => $user->getEmail(),
                    'roles' => json_encode($user->getRoles())
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                return $this->redirectToRoute('user');
            }
        }

        return $this->render('user/edit.html.twig', [ 'form' => $form->createView(), 'user' => $user ]);
    }

    /**
     * @Route("/user/{id}/remove", name="user_remove")
     */
    public function removeUser($id)
    {
        $response = $this->client->request('DELETE', $this->getParameter('api_url') . 'users/' . $id);

        return $this->redirectToRoute('user');
    }
}
