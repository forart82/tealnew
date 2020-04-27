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
    $this->offset = 0;
  }

  public function doDiagram()
  {

    // Get Results with user language.
    $this->getAlpha();
    // Get Coordinates.
    $this->getCoordinates();
    // Do choice offset.
    $this->doChoiceOffset();
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
        $this->results[$key]['offsetx'] = 0;
        $this->results[$key]['offsety'] = 0;
      }
    }
    $this->alpha = $this->alpha / $counter;
  }

  public function getCoordinates(): void
  {
    for ($i = 0; $i < count($this->results); $i++) {
      $s = 2 * $this->radius * (sin(deg2rad($this->next / 2)));
      $a = (180 - (sqrt(4 * pow($this->radius, 2) - (pow($s, 2))))) / 2;

      $this->results[$i] += [
        's' => $s,
        'a' => $a,
        'alpha' => $this->next,
        'y' => $this->radius + $this->getY() * $this->radius,
        'x' => $this->radius + $this->getX() * $this->radius,
      ];
      $this->next += $this->alpha;
    }
  }

  public function doChoiceOffset(): void
  {
    for ($i = 0; $i < count($this->results); $i++) {
      if($this->results[$i]['choice']==5)
      {
        $this->offset=0;

      }
      else
      {
        $this->next=$this->results[$i]['alpha'];
        $this->offset = -20 * $this->results[$i]['choice'];
        $this->results[$i]['y'] -= $this->getA();
        $this->results[$i]['x'] -= $this->getB();
      }
    }
    dump($this->results);
  }

  public function getX(): float
  {
    return cos(deg2rad($this->next));
  }

  public function getY(): float
  {
    return sin(deg2rad($this->next));
  }

  public function getA(): float
  {
    return $this->offset * cos(90 - $this->next);
  }

  public function getB(): float
  {
    return sqrt(pow($this->offset,2)-pow($this->getA(),2));
  }
}
