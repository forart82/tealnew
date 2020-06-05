<?php

namespace App\Controller;

use App\Entity\Navigations;
use App\Form\NavigationsType;
use App\Interfaces\ChangeList;
use App\Services\ChangeListValues;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NavigationsRepository;
use App\Repository\SubjectRepository;
use App\Repository\SvgRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/navigations")
 */
class NavigationsController extends AbstractController implements ChangeList
{

  private $request;
  private $entityManagerInterface;
  private $navigationsRepository;
  private $svgRepository;

  public function __construct(
    RequestStack $requestStack,
    EntityManagerInterface $entityManagerInterface,
    NavigationsRepository $navigationsRepository,
    SvgRepository $svgRepository
  ) {
    $this->request = $requestStack->getCurrentRequest();
    $this->entityManagerInterface = $entityManagerInterface;
    $this->navigationsRepository = $navigationsRepository;
    $this->svgRepository = $svgRepository;
  }

  /**
   * @Route("/", name="navigations", methods={"GET"})
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

      return $this->redirectToRoute('navigations');
    }

    return $this->render('MAIN/NEW.html.twig', [
      'element_teal' => $navigations,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/{id}/show", name="navigations_show", methods={"GET"})
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

      return $this->redirectToRoute('navigations');
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

    return $this->redirectToRoute('navigations');
  }

  /**
   * @Route("/changelist", name="navigations_change_list")
   */
  public function changeList(): Response
  {
    if ($this->request->isXmlHttpRequest()) {
      $data = $this->request->get("data");
      if (!empty($data['entity'])) {
          $obj = new ChangeListValues($this->entityManagerInterface);
          $obj->changeValues($data);
          return new JsonResponse($data);
      }
  }
    return new JsonResponse();
  }
}
