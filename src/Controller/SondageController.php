<?php

namespace App\Controller;


use App\Repository\LanguageRepository;
use App\Repository\ResultRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use App\Services\CheckLanguage;
use App\Services\Sondage;
use App\Services\SondagePositionCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Route("/sondage")
 */
class SondageController extends AbstractController
{

  private $subjectRepository;
  private $entityManagerInterface;
  private $resultRepository;
  private $request;
  private $languageRepository;
  private $parameterBagInterface;

  public function __construct(
    SubjectRepository $subjectRepository,
    EntityManagerInterface $entityManagerInterface,
    ResultRepository $resultRepository,
    UserRepository $userRepository,
    RequestStack $requestStack,
    LanguageRepository $languageRepository,
    ParameterBagInterface $parameterBagInterface
  ) {
    $this->subjectRepository = $subjectRepository;
    $this->entityManagerInterface = $entityManagerInterface;
    $this->resultRepository = $resultRepository;
    $this->userRepository = $userRepository;
    $this->request = $requestStack->getCurrentRequest();
    $this->languageRepository = $languageRepository;
    $this->parameterBagInterface = $parameterBagInterface;
  }

  /**
   * @Route("/{id<\d+>}", name="sondage", defaults={"id":1})
   */
  public function sondage($id)
  {

    // Do sondage.
    $sondage = new Sondage(
      $this->entityManagerInterface,
      $this->resultRepository,
      $this->request,
      $this->getUser()
    );
    $sondage->sondage();

    // Get langue to show result in right language.
    $language = new CheckLanguage(
      $this->languageRepository,
      $this->parameterBagInterface
    );

    // Find all subject with right langue.
    $subjects = $this->subjectRepository->findBy(['language' => $language->doLangue()]);
    $position = $this->subjectRepository->findOneById($id);
    $subject = $this->subjectRepository->findOneBy([
        'language' => $language->doLangue(),
        'position' => $position->getPosition()
      ]
    );
    // Find this result.
    $result = $this->resultRepository->findOneBy([
      'user' => $this->getUser()->getId(),
      'subject' => $subject->getId()
    ]);

    // Get all subject position to show subejcts in right order.
    $doPosition = $this->subjectRepository->findByLanguage($language->doLangue());
    $allPositions = SondagePositionCounter::doCountPosition($position, $doPosition);

    // Render web view.
    return $this->render('sondage/sondage.html.twig', [
      'subject' => $subject,
      'subjects' => $subjects,
      'result' => $result,
      'befor' => $allPositions["befor"],
      'after' => $allPositions["after"]
    ]);
  }
}
