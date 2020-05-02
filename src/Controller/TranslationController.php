<?php

namespace App\Controller;

use App\Entity\Translation;
use App\Form\TranslationType;
use App\Repository\TranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/translation")
 */
class TranslationController extends AbstractController
{
    /**
     * @Route("/", name="translation_index", methods={"GET"})
     */
    public function index(TranslationRepository $translationRepository): Response
    {
        return $this->render('MAIN/INDEX.html.twig', [
            'element_teal' => $translationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="translation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $translation = new Translation();
        $form = $this->createForm(TranslationType::class, $translation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($translation);
            $entityManager->flush();

            return $this->redirectToRoute('translation_index');
        }

        return $this->render('MAIN/NEW.html.twig', [
            'element_teal' => $translation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="translation_show", methods={"GET"})
     */
    public function show(Translation $translation): Response
    {
        return $this->render('MAIN/SHOW.html.twig', [
            'element_teal' => $translation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="translation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Translation $translation): Response
    {
        $form = $this->createForm(TranslationType::class, $translation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('translation_index');
        }

        return $this->render('MAIN/EDIT.html.twig', [
            'element_teal' => $translation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="translation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Translation $translation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$translation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($translation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('translation_index');
    }
}
