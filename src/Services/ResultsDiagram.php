<?php

namespace App\Services;

use App\Repository\ResultRepository;
use App\Entity\User;


class ResultsDiagram
{
  private $resultRepository;
  private $user;
  private $radius;
  private $center;

  private $alpha;
  private $next;
  private $results;
  private $offset;
  private $svgDiagram;
  private $svgDiagramUser;
  private $svgDiagramCompany;
  private $svgPointsCompany;

  public function __construct(
    ResultRepository $resultRepository,
    User $user,
    int $radius = 255,
    int $center = 145
  ) {
    $this->resultRepository = $resultRepository;
    $this->user = $user;
    $this->radius = $radius;

    $this->center = $center;
    $this->svgDiagram = "";
    $this->svgDiagramUser = "";
    $this->svgDiagramCompany = "";
    $this->svgPointsCompany = "";
    $this->alpha = 360;
    $this->next = -90;
    $this->results = [];
    $this->offset = 250;
  }

  public function doDiagram(): ?array
  {

    $this->createUserDiagram();
    $this->results = [];
    $this->alpha = 360;
    $this->createCompanyDiagram();



    return $this->results;
  }

  public function createUserDiagram()
  {
    // Get Results with user language.
    $this->createResultsUser();
    // Get Coordinates.
    $this->getCoordinates();
    // Add lines and pooints to svg.
    $this->createSvgDiagram("User");
    // Create png file diagram.
    $this->createPngDiagram();
    // Add bakcground to svg.
    $this->addSvgDiagramFileUser();
  }

  public function createCompanyDiagram()
  {
    // Get Results with user language.
    $this->createResultsCompany();
    // Get Coordinates.
    $this->getCoordinates();
    // Add lines and pooints to svg.
    $this->createSvgDiagram("Company");
    // Create png file diagram.
    $this->createPngDiagram();
    // Add bakcground to svg.
    $this->addSvgDiagramFileCompany();
    // Add bakcground to svg.
    $this->addSvgDiagramFilePoints();
  }

  public function createResultsUser(): void
  {
    $resultRepository = $this->resultRepository->allResultNotZeroUser($this->user);
    $counter = 0;
    foreach ($resultRepository as $key => $result) {
      $id = $result->getSubject()->getId();
      if ($result->getSubject()->getLanguage() == $this->user->getLanguage()) {
        $counter++;
        $this->results[$key]['choice'] = $result->getChoice();
        $this->results[$key]['id'] = $result->getId();
        $this->results[$key]['subject'] = $id;
      }
    }
    $this->alpha = $this->alpha / $counter;
  }

  public function createResultsCompany(): void
  {
    $resultRepository = $this->resultRepository->allResultNotZeroCompany($this->user->getCompany()->getId());
    $counter = 0;
    $values = [];
    foreach ($resultRepository as $key => $result) {
      $id = $result->getSubject()->getId();
      if (empty($values[$id])) {
        $values[$id]['choice'] = 0;
        $values[$id]['counter'] = 0;
      }
      $values[$id]['choice'] += $result->getChoice();
      $values[$id]['counter']++;
    }

    foreach ($resultRepository as $key => $result) {
      if ($result->getSubject()->getLanguage() == $this->user->getLanguage()) {
        $id = $result->getSubject()->getId();
        if(empty($values[$id]['subject']))
        {
          $this->results[$counter]['choice'] = $values[$id]['choice'] / $values[$id]['counter'];
          $this->results[$counter]['id'] = $result->getId();
          $this->results[$counter]['subject'] = $id;
          $values[$id]['subject']=true;
          $counter++;
        }
      }
    }
    $this->alpha = $this->alpha / $counter;
  }

  public function getCoordinates(): void
  {
    for ($i = 0; $i < count($this->results); $i++) {
      dump(count($this->results),$i);

      $this->offset = 50 * ($this->results[$i]['choice'] + 1);
      $this->results[$i] += [
        'alpha' => $this->next,
        'x' => $this->radius + cos(deg2rad($this->next)) * ($this->radius + 90),
        'y' => $this->radius + sin(deg2rad($this->next)) * ($this->radius + 90),
        'offsetX' => $this->radius + cos(deg2rad($this->next)) * $this->offset,
        'offsetY' => $this->radius + sin(deg2rad($this->next)) * $this->offset,
      ];
      $this->next += $this->alpha;
    }
  }

  public function createSvgDiagram(string $class): void
  {
    $pointX = 0;
    $pointY = 0;
    $firstX = $this->results[0]['offsetX'] + $this->center;
    $firstY = $this->results[0]['offsetY'] + $this->center;
    $this->svgDiagram = "";
    // Creation of lines
    foreach ($this->results as $key => $point) {
      $pointX = $point['offsetX'] + $this->center;
      $pointY = $point['offsetY'] + $this->center;
      $this->svgDiagram .= "<line class='diagramLine{$class}' x1={$pointX} y1={$pointY} x2={$firstX} y2={$firstY} />";
      $firstX = $pointX;
      $firstY = $pointY;
    }
    $firstX = $this->results[0]['offsetX'] + $this->center;
    $firstY = $this->results[0]['offsetY'] + $this->center;
    $this->svgDiagram .= "<line class='diagramLine{$class}' x1={$pointX} y1={$pointY} x2={$firstX} y2={$firstY} />";
    // Creation of Points
    foreach ($this->results as $key => $result) {
      $rectX1 = $this->results[$key]['offsetX'] + $this->center;
      $rectY1 = $this->results[$key]['offsetY'] + $this->center;
      $this->svgDiagram .= "<g><circle class='diagramCircle{$class}' id='{$result['subject']}_{$result['choice']}' cx={$rectX1} cy={$rectY1} r=10 />";
      $rectX1 = $this->results[$key]['x'] + $this->center - 15;
      $rectY1 = $this->results[$key]['y'] + $this->center;
      $this->svgDiagram .= "<text class='diagramText{$class}' x={$rectX1} y={$rectY1}>Test {$result['subject']} </text></g>";
    }
    $this->svgDiagram .= '</svg>';
  }

  public function createPngDiagram(): void
  {
    $png = imagecreatefrompng('contents/images/results/diagramBack.png');
    imagesetthickness($png, 4);
    $lineColor = imagecolorallocate($png, 255, 255, 255);
    $pointColor = imagecolorallocate($png, 255, 255, 255);
    $pointX = 0;
    $pointY = 0;
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

  public function getSvgDiagramUser(): string
  {
    return $this->svgDiagramUser;
  }
  public function getSvgDiagramCompany(): string
  {
    return $this->svgDiagramCompany;
  }
  public function getSvgPointsCompany(): string
  {
    return $this->svgPointsCompany;
  }

  public function addSvgDiagramFileUser(): void
  {
    $svg = file_get_contents('contents/images/results/diagramUser.svg');
    $svg = substr($svg, 0, -7);
    $this->svgDiagramUser = $svg;
    $this->svgDiagramUser .= $this->svgDiagram;
  }

  public function addSvgDiagramFileCompany(): void
  {
    $svg = file_get_contents('contents/images/results/diagramCompany.svg');
    $svg = substr($svg, 0, -7);
    $this->svgDiagramCompany = $svg;
    $this->svgDiagramCompany .= $this->svgDiagram;
  }

  public function addSvgDiagramFilePoints(): void
  {
    $this->svgDiagramUser = substr($this->svgDiagramUser, 0, -7);
    $this->svgDiagramUser .= $this->svgDiagram;
  }

  public function getResults()
  {
    return $this->results;
  }
}
