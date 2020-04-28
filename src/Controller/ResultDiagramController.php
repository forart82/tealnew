<?php

namespace App\Controller;

use App\Services\ResultsToSvgDiagram;
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
        $user = $this->userRepository->findOneById(3);

        $resultsToSvgDiagram = new ResultsToSvgDiagram(
            $this->resultRepository,
            $user
        );


        imagesetthickness($im, 4);

        foreach ($diagram as $key => $point) {
            $bunt = $key * 10;
            $pointX = $point['offsetX'] + $offset;
            $pointY = $point['offsetY'] + $offset;
            imageline($im, $pointX, $pointY, $firstX, $firstY, $bunt);

            $firstX = $pointX;
            $firstY = $pointY;
        }
        $firstX = $diagram[0]['offsetX'] + $offset;
        $firstY = $diagram[0]['offsetY'] + $offset;

        imageline($im, $pointX, $pointY, $firstX, $firstY, $bunt);

        foreach ($diagram as $key => $dia) {
            $rectX1 = $diagram[$key]['offsetX'] + $offset;
            $rectY1 = $diagram[$key]['offsetY'] + $offset;
            $color = $key * 20;
            imagefilledellipse($im, $rectX1, $rectY1, 20, 20, 255255255);
        }

        imagepng($im, 'contents/images/site/diagram3.png');
        imagedestroy($im);

        foreach ($diagram as $key => $dia) {
            $rectX1 = $diagram[$key]['offsetX'] + $offset;
            $rectY1 = $diagram[$key]['offsetY'] + $offset;
            $color = $key * 20;
            $svgDiagram .= "<circle cx={$rectX1} cy={$rectY1} r=10 style='fill:rgb(0,{$color},{$color});' />";
        }



        // foreach ($diagram as $key => $dia) {
        //     $color = $key * 20;
        //     $counter--;
        //     $rectX2 = $diagram[$key]['x'] + $rectCorp;
        //     $rectY2 = $diagram[$key]['y'] + $rectCorp;
        //     if ($counter != 0) {

        //         $rectX1 = $diagram[$key+1]['x'] + $rectCorp;
        //         $rectY1 = $diagram[$key+1]['y'] + $rectCorp;
        //         $string .= "
        //         <line x1={$rectX2} y1={$rectY2} x2={$rectX1} y2={$rectY1} style='stroke:rgb(0,{$color},{$color});stroke-width:2' />";
        //         // <circle id=circlel cx={$rectX1} cy={$rectY1} r=10 style='fill:rgb(0,{$color},{$color});' />";
        //     }else
        //     {
        //         $string .= "<line x1={$rectX1} y1={$rectY1} x2={$first['x']} y2={$first['y']} style='stroke:rgb(0,{$color},{$color});stroke-width:2' />";
        //         // <circle id=circle2 cx={$first['x']} cy={$first['y']} r=10 style='fill:rgb(0,{$color},{$color});'/>";
        //     }
        // }






        $svgDiagram .= '<use id="use" xlink:href="#circle2" /></svg>';
        return $this->render('result_diagram/result_diagram.html.twig', [
            'diagram' => $diagram,
            'svgDiagram' => $svgDiagram,

        ]);
    }
}
