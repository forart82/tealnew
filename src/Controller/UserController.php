<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserVerificationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $request;
    private $userRepository;

    public function __construct(
        RequestStack $requestStack,
        UserRepository $userRepository
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="user", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('MAIN/INDEX.html.twig', [
            'element_teal' => $this->userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('MAIN/NEW.html.twig', [
            'element_teal' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('MAIN/SHOW.html.twig', [
            'element_teal' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('MAIN/EDIT.html.twig', [
            'element_teal' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verification/{email}/{token}", name="user_verification", methods={"GET"},
     * defaults={"email"="email@email.com", "token"="0"})
     */
    public function verification($email, $token): Response
    {
        $user = $this->userRepository->findOneByEmail($email);
        $userToken = null;


        if ($user); {
            $token = preg_replace('/[\W]/', '', $token);
            $userToken = $user->getToken();
            if ($token == $userToken) {
                $form = $this->createForm(UserVerificationType::class, $user);
                $form->handleRequest($this->request);
                if ($form->isSubmitted() && $form->isValid()) {
                }
                return $this->render('user/verification.html.twig',[
                    'form'=>$form->createView(),
                ]);
            }
        }
        return $this->redirectToRoute('app_login');
    }
    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $this->request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user');
    }
}
