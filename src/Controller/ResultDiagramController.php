<?php

namespace App\Controller;

use App\Services\ResultsDiagram;
use App\Repository\UserRepository;
use App\Repository\ResultRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\CurlHttpClient;

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
        $client = HttpClient::create();
        $response = $client->request('GET','https://www.linkedin.com/oauth/v2/accessToken?grant_type=client_credentials&client_id=86qa1cgyg80rud&client_secret=s1QNIeC55rSWoXuQ');
        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        // $client = new CurlHttpClient();
        // $response = $client->request('GET', 'https://www.linkedin.com/oauth/v2/accessToken?grant_type=client_credentials&client_id=86qa1cgyg80rud&client_secret=s1QNIeC55rSWoXuQ');
        // $contents = $response->getContent();
        dump($contents);

        $user = $this->userRepository->findOneById(3);

        $resultsDiagram = new ResultsDiagram(
            $this->resultRepository,
            $user
        );
        $resultsDiagram->doDiagram();
        $resultsDiagram->createPngDiagram();
        return $this->render('result_diagram/diagram.html.twig', [
            'svgDiagram' => $resultsDiagram->getSvgDiagram(),
            'results' => $resultsDiagram->getResults(),
        ]);
    }
}
