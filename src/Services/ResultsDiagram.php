<?php

namespace App\Services;

use App\Repository\ResultRepository;
use App\Entity\User;


class ResultsDiagram
{
  private $resultRepository;
  private $user;
  private $radius;
  private $clRed;
  private $clGreen;
  private $clBlue;
  private $cpRed;
  private $cpGreen;
  private $cpBlue;
  private $stroke;
  private $center;

  private $alpha;
  private $next;
  private $results;
  private $offset;

  private $svgDiagram;

  public function __construct(
    ResultRepository $resultRepository,
    User $user,
    int $radius = 255,
    int $clRed = 100,
    int $clGreen = 50,
    int $clBlue = 100,
    int $cpRed = 50,
    int $cpGreen = 100,
    int $cpBlue = 255,
    int $stroke = 5,
    int $center = 45
  ) {
    $this->resultRepository = $resultRepository;
    $this->user = $user;
    $this->radius = $radius;
    $this->clRed = $clRed;
    $this->clGreen = $clGreen;
    $this->clBlue = $clBlue;
    $this->cpRed = $cpRed;
    $this->cpGreen = $cpGreen;
    $this->cpBlue = $cpBlue;
    $this->stroke = $stroke;
    $this->center = $center;
    $this->svgDiagram = "";
    $this->alpha = 360;
    $this->next = -90;
    $this->results = [];
    $this->offset = 250;
  }

  public function doDiagram(): ?array
  {

    // Get Results with user language.
    $this->getAlpha();
    // Get Coordinates.
    $this->getCoordinates();
    // Add bakcground to svg.
    $this->getSvgDiagramFile();
    // Add lines and pooints to svg.
    $this->createSvgDiagram();
    // Create png file diagram.
    $this->createPngDiagram();

    return $this->results;
  }

  public function getAlpha(): void
  {
    $resultRepository = $this->resultRepository->allResultNotZero($this->user);
    $counter = 0;
    foreach ($resultRepository as $key => $result) {
      if ($result->getSubject()->getLanguage() == $this->user->getLanguage()) {
        $counter++;
        $this->results[$key]['choice'] = $result->getChoice();
        $this->results[$key]['id'] = $result->getId();
      }
    }
    $this->alpha = $this->alpha / $counter;
  }

  public function getCoordinates(): void
  {
    for ($i = 0; $i < count($this->results); $i++) {
      $this->offset = 50 * ($this->results[$i]['choice'] + 1);
      $this->results[$i] += [
        'alpha' => $this->next,
        'x' => $this->radius + cos(deg2rad($this->next)) * $this->radius,
        'y' => $this->radius + sin(deg2rad($this->next)) * $this->radius,
        'offsetX' => $this->radius + cos(deg2rad($this->next)) * $this->offset,
        'offsetY' => $this->radius + sin(deg2rad($this->next)) * $this->offset,
      ];
      $this->next += $this->alpha;
    }
  }


  public function createSvgDiagram(): void
  {
    $pointX=0;
    $pointY=0;
    $firstX = $this->results[0]['offsetX'] + $this->center;
    $firstY = $this->results[0]['offsetY'] + $this->center;
    // Creation of lines
    foreach ($this->results as $key => $point) {
      $pointX = $point['offsetX'] + $this->center;
      $pointY = $point['offsetY'] + $this->center;
      $this->svgDiagram .= "<line x1={$pointX} y1={$pointY} x2={$firstX} y2={$firstY}
        style='stroke:rgb({$this->clRed},{$this->clGreen},{$this->clBlue});stroke-width:{$this->stroke};' />";
      $firstX = $pointX;
      $firstY = $pointY;
    }
    $firstX = $this->results[0]['offsetX'] + $this->center;
    $firstY = $this->results[0]['offsetY'] + $this->center;
    $this->svgDiagram .= "<line x1={$pointX} y1={$pointY} x2={$firstX} y2={$firstY} style='stroke:rgb({$this->clRed},{$this->clGreen},{$this->clBlue});stroke-width:{$this->stroke};' />";
    // Creation of Points
    foreach ($this->results as $key => $dia) {
      $rectX1 = $this->results[$key]['offsetX'] + $this->center;
      $rectY1 = $this->results[$key]['offsetY'] + $this->center;
      $this->svgDiagram .= "<circle cx={$rectX1} cy={$rectY1} r=10 style='fill:rgb({$this->cpRed}, {$this->cpGreen}, {$this->cpBlue});' />";
      $rectX1+=$rectX1/10;
      $this->svgDiagram .= "<text class='diagramText' x={$rectX1 } y={$rectY1} style='fill:rgb({$this->cpRed}, {$this->cpGreen}, {$this->cpBlue});'>test</text>";
    }
    $this->svgDiagram .= '<use id="use" xlink:href="#circle2" /></svg>';
  }

  public function createPngDiagram(): void
  {
    $png = imagecreatefrompng('contents/images/results/diagramBack.png');
    imagesetthickness($png, 4);
    $lineColor=imagecolorallocate($png,$this->clRed,$this->clGreen,$this->clBlue);
    $pointColor = imagecolorallocate($png, $this->cpRed, $this->cpGreen, $this->cpBlue);
    $pointX=0;
    $pointY=0;
    $firstX = $this->results[0]['offsetX'] + $this->center;
    $firstY = $this->results[0]['offsetY'] + $this->center;
    foreach ($this->results as $key => $point) {
      $pointX = $point['offsetX'] + $this->center;
      $pointY = $point['offsetY'] + $this->center;
      imageline($png, $pointX, $pointY, $firstX, $firstY, $lineColor);

      $firstX = $pointX;
      $firstY = $pointY;
    }
    $firstX = $this->results[0]['offsetX'] + $this->center;
    $firstY = $this->results[0]['offsetY'] + $this->center;

    imageline($png, $pointX, $pointY, $firstX, $firstY, $lineColor);

    foreach ($this->results as $key => $dia) {
      $rectX1 = $this->results[$key]['offsetX'] + $this->center;
      $rectY1 = $this->results[$key]['offsetY'] + $this->center;
      imagefilledellipse($png, $rectX1, $rectY1, 20, 20, $pointColor);
    }

    imagepng($png, 'contents/images/results/diagram-user-id.png');
    imagedestroy($png);
  }

  public function getSvgDiagram(): string
  {
    return $this->svgDiagram;
  }

  public function getSvgDiagramFile(): void
  {
    $this->svgDiagram=file_get_contents('contents/images/results/diagramBack.svg');
    $this->svgDiagram=substr($this->svgDiagram,0,-7);
  }

  public function getResults()
  {
    return $this->results;
  }
}
