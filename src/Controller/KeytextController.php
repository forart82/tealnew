<?php

namespace App\Controller;

use App\Entity\Keytext;
use App\Form\KeytextType;
use App\Repository\KeytextRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/keytext")
 */
class KeytextController extends AbstractController
{
    /**
     * @Route("/", name="keytext_index", methods={"GET"})
     */
    public function index(KeytextRepository $keytextRepository): Response
    {
        return $this->render('keytext/index.html.twig', [
            'keytexts' => $keytextRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="keytext_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $keytext = new Keytext();
        $form = $this->createForm(KeytextType::class, $keytext);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($keytext);
            $entityManager->flush();

            return $this->redirectToRoute('keytext_index');
        }

        return $this->render('keytext/new.html.twig', [
            'keytext' => $keytext,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="keytext_show", methods={"GET"})
     */
    public function show(Keytext $keytext): Response
    {
        return $this->render('keytext/show.html.twig', [
            'keytext' => $keytext,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="keytext_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Keytext $keytext): Response
    {
        $form = $this->createForm(KeytextType::class, $keytext);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('keytext_index');
        }

        return $this->render('keytext/edit.html.twig', [
            'keytext' => $keytext,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="keytext_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Keytext $keytext): Response
    {
        if ($this->isCsrfTokenValid('delete'.$keytext->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($keytext);
            $entityManager->flush();
        }

        return $this->redirectToRoute('keytext_index');
    }
}
