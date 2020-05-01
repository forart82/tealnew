<?php

namespace App\Controller;

use App\Entity\Navigations;
use App\Form\NavigationsType;
use App\Repository\NavigationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/navigations")
 */
class NavigationsController extends AbstractController
{
  /**
   * @Route("/", name="navigations_index", methods={"GET"})
   */
  public function index(NavigationsRepository $navigationsRepository): Response
  {
    return $this->render('MAIN/INDEX.html.twig', [
      'element_teal' => $navigationsRepository->findAll(),
    ]);
  }

  /**
   * @Route("/new", name="navigations_new", methods={"GET","POST"})
   */
  public function new(Request $request): Response
  {
    $navigations = new Navigations();
    $form = $this->createForm(NavigationsType::class, $navigations);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($navigations);
      $entityManager->flush();

      return $this->redirectToRoute('navigations_index');
    }

    return $this->render('MAIN/NEW.html.twig', [
      'element_teal' => $navigations,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/{id}", name="navigations_show", methods={"GET"})
   */
  public function show(Navigations $navigations): Response
  {
    return $this->render('MAIN/SHOW.html.twig', [
      'element_teal' => $navigations,
    ]);
  }

  /**
   * @Route("/{id}/edit", name="navigations_edit", methods={"GET","POST"})
   */
  public function edit(Request $request, Navigations $navigations): Response
  {
    $form = $this->createForm(NavigationsType::class, $navigations);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('navigations_index');
    }

    return $this->render('MAIN/EDIT.html.twig', [
      'element_teal' => $navigations,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/{id}", name="navigations_delete", methods={"DELETE"})
   */
  public function delete(Request $request, Navigations $navigations): Response
  {
    if ($this->isCsrfTokenValid('delete' . $navigations->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($navigations);
      $entityManager->flush();
    }

    return $this->redirectToRoute('navigations_index');
  }
}
