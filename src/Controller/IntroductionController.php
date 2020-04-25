<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class IntroductionController extends AbstractController
{
    /**
     * @Route("/", name="introduction")
     */
    public function index(): Response
    {
        return $this->render('introduction/index.html.twig', [
            'controller_name' => 'IntroductionController',
        ]);
    }
}
