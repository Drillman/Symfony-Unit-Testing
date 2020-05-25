<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\Todolist;
use App\Form\TodolistType;
use App\Repository\TodolistRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/todolist")
 */
class TodolistController extends AbstractController
{
    /**
     * @Route("/", name="todolist_index", methods={"GET"})
     */
    public function index(TodolistRepository $todolistRepository,SerializerInterface $serializer): Response
    {
        $res = $serializer->serialize($todolistRepository->findAll(), 'json');
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/new", name="todolist_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $todolist = new Todolist();
        $user = new User();
        $data = \json_decode($request->getContent());
        var_dump($data);
        $user->setId($data->id)
        ->setEmail($data->email)
        ->setAge($data->age)
        ->setPassword($data->password);
        $todolist->setUser($user);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($todolist);
        $entityManager->flush();
        $response = new Response(null,201,[]);
        return $response;
        
    }

    /**
     * @Route("/{id}", name="todolist_show", methods={"GET"})
     */
    public function show(Todolist $todolist): Response
    {
        return $this->render('todolist/show.html.twig', [
            'todolist' => $todolist,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="todolist_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Todolist $todolist): Response
    {
        $form = $this->createForm(TodolistType::class, $todolist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render('todolist/edit.html.twig', [
            'todolist' => $todolist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="todolist_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Todolist $todolist): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todolist->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($todolist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('todolist_index');
    }
}
