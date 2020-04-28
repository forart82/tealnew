<?php

namespace App\Services;

use App\Repository\ResultRepository;
use App\Entity\User;


class ResultsToSvgDiagram
{
  private $resultRepository;
  private $user;
  private $radius;

  private $alpha;
  private $next;
  private $results;
  private $offset;

  public function __construct(
    ResultRepository $resultRepository,
    User $user,
    int $radius = 250
  ) {
    $this->resultRepository = $resultRepository;
    $this->user = $user;
    $this->radius = $radius;
    $this->alpha = 360;
    $this->next = -90;
    $this->resutls = [];
    $this->offset = 250;
  }

  public function doDiagram()
  {

    // Get Results with user language.
    $this->getAlpha();
    // Get Coordinates.
    $this->getCoordinates();

    $this->createSvgDiagram();

    // Do choice offset.
    // $this->doChoiceOffset();
    // dump($this->results);

    return $this->results;
  }

  public function getAlpha(): void
  {
    $resultRepository = $this->resultRepository->allResultNotZero($this->user);
    $counter = 0;
    foreach ($resultRepository as $key => $result) {
      if ($result->getIdSubject()->getLanguage() == $this->user->getLanguage()) {
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
      $radiusOffset = 50 * ($this->results[$i]['choice']+1);
      $this->results[$i] += [
        'alpha' => $this->next,
        'x' => $this->radius + cos(deg2rad($this->next)) * $this->radius,
        'y' => $this->radius + sin(deg2rad($this->next)) * $this->radius,
        'offsetX' => $this->radius + cos(deg2rad($this->next))* $radiusOffset,
        'offsetY' => $this->radius + sin(deg2rad($this->next))* $radiusOffset,
      ];
      $this->next += $this->alpha;
    }
  }


  public function createSvgDiagram()
  {
    $offset = 50;
    $svgDiagram = '<svg height="100vh" width="100vw">';
    $diagram = $resultsToSvgDiagram->doDiagram();
    $im = imagecreatefrompng('contents/images/site/diagramBack.png');
    $color = "blue";
    $stroke = 5;
    $firstX = $diagram[0]['offsetX'] + $offset;
    $firstY = $diagram[0]['offsetY'] + $offset;


    $firstX = $diagram[0]['offsetX'] + $offset;
    $firstY = $diagram[0]['offsetY'] + $offset;
    foreach ($diagram as $key => $point) {
        $bunt = $key * 10;
        $pointX = $point['offsetX'] + $offset;
        $pointY = $point['offsetY'] + $offset;
        $svgDiagram .= "<line x1={$pointX} y1={$pointY} x2={$firstX} y2={$firstY} style='stroke:rgb({$bunt},0,0);stroke-width:{$stroke}' />";
        $firstX = $pointX;
        $firstY = $pointY;
    }
    $firstX = $diagram[0]['offsetX'] + $offset;
    $firstY = $diagram[0]['offsetY'] + $offset;
    $svgDiagram .= "<line x1={$pointX} y1={$pointY} x2={$firstX} y2={$firstY} style='stroke:{$color};stroke-width:{$stroke}' />";

  }

}
