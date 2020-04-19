<?php

namespace App\Controller;

use App\Entity\Todolist;
use App\Form\TodolistType;
use App\Repository\TodolistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todolist")
 */
class TodolistController extends AbstractController
{
    /**
     * @Route("/", name="todolist_index", methods={"GET"})
     */
    public function index(TodolistRepository $todolistRepository): Response
    {
        return $this->render('todolist/index.html.twig', [
            'todolists' => $todolistRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="todolist_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $todolist = new Todolist();
        $form = $this->createForm(TodolistType::class, $todolist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todolist);
            $entityManager->flush();

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render('todolist/new.html.twig', [
            'todolist' => $todolist,
            'form' => $form->createView(),
        ]);
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
