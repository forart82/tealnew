<?php

namespace App\Controller;

use App\Services\TestResult;
use App\Repository\UserRepository;
use App\Repository\ResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


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
    public function index()
    {

        $test = new TestResult($this->userRepository, $this->resultRepository);
        $string = '<svg height="1000" width="1000">';
        $diagram = $test->doDiagramm();
        $rectCorp = 100;
        $first = $diagram[0];
        $first['x']+=$rectCorp;
        $first['y']+=$rectCorp;
        $counter = count($diagram);
        foreach ($diagram as $key => $dia) {
            $color = $key * 20;
            $counter--;
            $rectX2 = $diagram[$key]['x'] + $rectCorp;
            $rectY2 = $diagram[$key]['y'] + $rectCorp;
            if ($counter != 0) {

                $rectX1 = $diagram[$key+1]['x'] + $rectCorp;
                $rectY1 = $diagram[$key+1]['y'] + $rectCorp;
                $string .= "
                <line x1={$rectX2} y1={$rectY2} x2={$rectX1} y2={$rectY1} style='stroke:rgb(0,{$color},{$color});stroke-width:2' />";
                // <circle id=circlel cx={$rectX1} cy={$rectY1} r=10 style='fill:rgb(0,{$color},{$color});' />";
            }else
            {
                $string .= "<line x1={$rectX1} y1={$rectY1} x2={$first['x']} y2={$first['y']} style='stroke:rgb(0,{$color},{$color});stroke-width:2' />";
                // <circle id=circle2 cx={$first['x']} cy={$first['y']} r=10 style='fill:rgb(0,{$color},{$color});'/>";
            }
        }

        foreach($diagram as $key => $dia)
        {
            $rectX1 = $diagram[$key]['x'] + $rectCorp;
            $rectY1 = $diagram[$key]['y'] + $rectCorp;
            $color=$key*20;
            $string .= "<circle cx={$rectX1} cy={$rectY1} r=10 style='fill:rgb(0,{$color},{$color});' />";

        }

        $string .= '<use id="use" xlink:href="#circle2" /></svg>';
        return $this->render('result_diagram/result_diagram.html.twig', [
            'diagram' => $diagram,
            'string' => $string
        ]);
    }
}
