<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $userJson = $serializer->serialize($userRepository->findAll(), 'json');
        $response = new Response($userJson);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $data = \json_decode($request->getContent());
        $user->setName($data->name)
            ->setFirstname($data->firstname)
            ->setEmail($data->email)
            ->setPassword($data->password)
            ->setAge($data->age);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $response = new Response(null,201,[]);
        return $response;
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, SerializerInterface $serializer): Response
    {
        $userJson = $serializer->serialize($user, 'json');
        $response = new Response($userJson);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
