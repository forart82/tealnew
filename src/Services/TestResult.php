<?php

namespace App\Services;

use App\Repository\ResultRepository;
use App\Repository\UserRepository;


class TestResult
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

  public function doDiagramm()
  {
    $resultRepository = $this->resultRepository->allResultNotZero(3);
    $counter = 0;
    $results = [];
    $diagrammArray = [
      'top' => [90, 0,], // + +
      'right' => [180, 90], // - +
      'bottom' => [90, 180], // - -
      'left' => [0, 90], //  + -
    ];
    $quantity = 360;

    foreach ($resultRepository as $result) {
      if ($result->getIdSubject()->getLanguage() == "fr") {
        $counter++;
      }
    }
    $x = 0;
    $alpha = 360 / $counter;
    $doArray[] = 0;
    $x = $alpha;
    for ($i = 0; $i < $counter; $i++) {
      $distance = 2 * 90 * (sin(deg2rad($x / 2)));
      $a = (180 - (sqrt(4 * pow(90, 2) - (pow($distance, 2))))) / 2;
      $doArray[] = [
        'distance' => $distance,
        'a' => $a,
        'alpha' => $x,
        'x' => cos(deg2rad($x)) * 90,
        'y' => sin(deg2rad($x)) * 90,
      ];
      $x += $alpha;
      dump($doArray, $x);
    }





    dd(array_pop($doArray), $counter, $quantity, $doArray);
  }
}
