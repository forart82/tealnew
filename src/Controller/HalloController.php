<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class HalloController extends AbstractController
{
    /**
     * @Route("/hallo", name="hallo")
     */
    public function index(Request $request)
    {
        $date = new DateTime();
        dump($request);
        $revenues = [
            0 => [
                'date' => (new DateTime("01-04-2020"))->getTimestamp(),
                'amount' => 1000,
            ],
            1 => [
                'date' => (new DateTime("01-12-2019"))->getTimestamp(),
                'amount' => 950,
            ]
        ];


        return $this->render('hallo/index.html.twig', [
            "mainRevenue" => $revenues,
            "string" => $string
        ]);
    }

    /**
     * @Route("/hallo2", name="index")
     */
    public function index2()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
