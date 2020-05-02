<?php

namespace App\Controller;

use App\Entity\Result;
use App\Services\InsertReponse;
use App\Repository\SvgRepository;
use App\Repository\ResultRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use App\Services\SondagePositionCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/sondage")
 */
class SondageController extends AbstractController
{

  private $subjectRepository;
  private $manager;
  private $resultRepository;
  public function __construct(
      SubjectRepository $subjectRepository,
      EntityManagerInterface $manager,
      ResultRepository $resultRepository,
      UserRepository $userRepository
  ) {
      $this->subjectRepository = $subjectRepository;
      $this->manager = $manager;
      $this->resultRepository = $resultRepository;
      $this->userRepository = $userRepository;
  }

  /**
   * @Route("/{id<\d+>}", name="sondage", defaults={"id":1})
   */
  public function sondage($id, Request $request, SvgRepository $svgRepository)
  {

    $message = "";
    $svgs = $svgRepository->findLikeAnswer("answer");
    $subjects = $this->subjectRepository->findBy(['language' => 'fr']);
    $insert = new InsertReponse();
    if (
      !$this->resultRepository->findOneBy([
        'user' => $this->getUser()->getId(),
        'subject' => $request->request->get('subjectId')
      ])
      && $request->request->get('subjectId')
    ) {
      $result = new Result();
      for ($i = 1; $i < 6; $i++) {
        if ($request->request->get($i)) {
          $subjectId = $request->request->get('subjectId');
          $insert->insert(
            $result,
            $this->manager,
            $i,
            $this->subjectRepository->findOneById($subjectId),
            $this->getUser()
          );
        }
      }
    } else {
      for ($i = 1; $i < 6; $i++) {
        if ($request->request->get($i)) {
          $subjectId = $request->request->get('subjectId');
          $result = $this->resultRepository->findOneBy([
            'user' => $this->getUser()->getId(),
            'subject' => $request->request->get('subjectId')
          ]);
          $insert->update($result, $this->manager, $i, $subjectId);
        }
      }
    }
    $position = $this->subjectRepository->findOneById($id);
    $subject = $this->subjectRepository->findOneBy(
      array(
        'language' => 'fr',
        'position' => $position->getPosition()
      )
    );
    $result = $this->resultRepository->findOneBy([
      'user' => $this->getUser()->getId(),
      'subject' => $subject->getId()
    ]);
    $results = $this->resultRepository->findAll();
    $doPosition = $this->subjectRepository->findByLanguage('fr');
    $allPositions = SondagePositionCounter::doPositionCounter($position, $doPosition);
    return $this->render('sondage/sondage.html.twig', [
      'message' => $message,
      'subject' => $subject,
      'subjects' => $subjects,
      'result' => $result,
      'svgs' => $svgs,
      'results' => $results,
      'befor' => $allPositions["befor"],
      'after' => $allPositions["after"]
    ]);
  }
}
