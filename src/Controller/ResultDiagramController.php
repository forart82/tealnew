<?php

namespace App\Controller;

use App\Services\ResultsDiagram;
use App\Repository\UserRepository;
use App\Repository\ResultRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/resultdiagram")
 */
class ResultDiagramController extends AbstractController
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
     * @Route("/", name="result_diagram")
     */
    public function diagram(): Response
    {
        $user = $this->userRepository->findOneById(3);

        $resultsDiagram = new ResultsDiagram(
            $this->resultRepository,
            $user
        );
        $resultsDiagram->doDiagram();
        $resultsDiagram->createPngDiagram();

        return $this->render('result_diagram/result_diagram.html.twig', [
            'svgDiagram' => $resultsDiagram->getSvgDiagram(),

        ]);
    }
}
