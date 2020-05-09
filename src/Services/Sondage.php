<?php

/**
 * @file
 * Contains a class to controlle user input for subjet questions.
 */

namespace App\Services;

use App\Entity\User;
use App\Entity\Result;
use App\Repository\ResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class Sondage
{
  private $entityManagerInterface;
  private $resultRepository;
  private $request;
  private $user;

  public function __construct(
    EntityManagerInterface $entityManagerInterface,
    ResultRepository $resultRepository,
    Request $request,
    User $user
  )
  {
    $this->entityManagerInterface=$entityManagerInterface;
    $this->resultRepository=$resultRepository;
    $this->request=$request;
    $this->user=$user;
  }

  public function sondage()
  {

    $insert = new InsertReponse();
    if (
      !$this->resultRepository->findOneBy([
        'user' => $this->user->getId(),
        'subject' => $this->request->request->get('subjectId')
      ])
      && $this->request->request->get('subjectId')
    ) {


      $result = new Result();
      for ($i = 1; $i < 6; $i++) {
        if ($this->request->request->get($i)) {
          $subjectId = $this->request->request->get('subjectId');
          $insert->insert(
            $result,
            $this->entityManagerInterface,
            $i,
            $this->subjectRepository->findOneById($subjectId),
            $this->user
          );
        }
      }
    } else {
      for ($i = 1; $i < 6; $i++) {
        if ($this->request->get($i)) {
          $subjectId = $this->request->request->get('subjectId');
          $result = $this->resultRepository->findOneBy([
            'user' => $this->user->getId(),
            'subject' => $this->request->request->get('subjectId')
          ]);

          $insert->update($result, $this->entityManagerInterface, $i, $subjectId);
        }

      }
    }
  }
}
