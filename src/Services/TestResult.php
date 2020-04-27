<?php

namespace App\Services;

use App\Repository\ResultRepository;
use App\Repository\UserRepository;
use App\Entity\User;


class TestResult
{
  private $resultRepository;
  private $user;
  private $radius;

  private $alpha;
  private $next;
  private $results;
  private $choice;

  public function __construct(
    ResultRepository $resultRepository,
    User $user,
    int $radius = 250
  ) {
    $this->resultRepository = $resultRepository;
    $this->user=$user;
    $this->radius=$radius;
    $this->alpha=360;
    $this->next=-90;
    $this->resutls=[];
    $this->choice=[];
  }

  public function doDiagram()
  {

    // Get Results with user language.
    $this->getAlpha();
    // Get Coordinates.
    $this->getCoordinates();
    // Do choice offset.
    // $this->doChoiceOffset();

    dump($this->results);
    return $this->results;
  }

  public function getAlpha():void
  {
    $resultRepository = $this->resultRepository->allResultNotZero($this->user);
    $counter=0;
    foreach ($resultRepository as $key => $result) {
      if ($result->getIdSubject()->getLanguage() == $this->user->getLanguage()) {
        $counter++;
        $this->results[$key]['choice']=$result->getChoice();
        $this->results[$key]['id']=$result->getId();

      }
    }
    $this->alpha=$this->alpha/$counter;
  }

  public function getCoordinates():void
  {
    for ($i=0;$i<count($this->results);$i++) {
      $s = 2 * $this->radius * (sin(deg2rad($this->next / 2)));
      $a = (180 - (sqrt(4 * pow($this->radius, 2) - (pow($s, 2))))) / 2;
      $this->results[$i]+=[
        's' => $s,
        'a' => $a,
        'alpha' => $this->next,
        'x' => $this->radius + sin(deg2rad($this->next)) * $this->radius,
        'y' => $this->radius + cos(deg2rad($this->next)) * $this->radius,
      ];
      $this->next += $this->alpha;
    }
  }

  public function doChoiceOffset():void{
    foreach($this->results as $key => $result)
    {
      $this->results[$key]['x']+=-10*$result['choice'];
      $this->results[$key]['y']+=-10*$result['choice'];
    }
  }
}
