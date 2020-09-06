<?php

namespace App\Controller;

use App\Services\ResultsDiagram;
use App\Repository\UserRepository;
use App\Repository\ResultRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/resultdiagram")
 */
class ResultDiagramController extends AbstractController
{

  private $userRepository;
  private $resultRepository;
  private $request;
  private $subjectRepository;

  public function __construct(
    UserRepository $userRepository,
    ResultRepository $resultRepository,
    RequestStack $requestStack,
    SubjectRepository $subjectRepository
  ) {
    $this->userRepository = $userRepository;
    $this->resultRepository = $resultRepository;
    $this->request = $requestStack->getCurrentRequest();
    $this->subjectRepository = $subjectRepository;
  }
  /**
   * @Route("/", name="result_diagram")
   */
  public function diagram(): Response
  {

    $user = $this->userRepository->findOneById($this->getUser());

    $resultsDiagram = new ResultsDiagram(
      $this->resultRepository,
      $user
    );
    $resultsDiagram->doDiagram();
    $resultsDiagram->createPngDiagram();

    return $this->render('result_diagram/diagram.html.twig', [
      'svgUser' => $resultsDiagram->getSvgDiagramUser(),
      'results' => $resultsDiagram->getResults(),
    ]);
  }
  /**
   * @Route("/ajaxgetsubject", name="ajaxgetsubject" )
   */
  public function ajaxGetSubject(): Response
  {

    if ($this->request->isXmlHttpRequest()) {
      if ($this->request->get('id')) {
        $values = explode('_', $this->request->get('id'));
      }
      if ($subject = $this->subjectRepository->findOneById($values[0])) {
        return new JsonResponse([
          'question' => $subject->getQuestion(),
          'choice' => $values[1],
        ]);
      }
    }

    return new JsonResponse();
  }
}
