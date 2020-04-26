<?php

namespace App\Controller;

use App\Services\TestResult;
use App\Repository\UserRepository;
use App\Repository\ResultRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/introduction")
 */
class IntroductionController extends AbstractController
{

    private $userRepository;
    private $resultRepository;

    public function __construct(
        UserRepository $userRepository,
        ResultRepository $resultRepository
    ) {
        $this->userRepository = $userRepository;
        $this->resultRepository = $resultRepository;
    }
    /**
     * @Route("/", name="introduction")
     */
    public function introduction(): Response
    {
        $test=new TestResult($this->userRepository, $this->resultRepository);
        $test->doDiagramm();
        return $this->render('introduction/introduction.html.twig', [
        ]);
    }
}
