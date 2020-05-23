<?php

/**
 * @file
 * Contains a class who modify values in _list.html.twig after user input
 */


namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class ChangeListValues
{

  private $entityManagerInterface;

  public function __construct(
    EntityManagerInterface $entityManagerInterface
  ) {
    $this->entityManagerInterface = $entityManagerInterface;
  }

  public function changeValues($repository, array $data)
  {
    $obj = $repository->findOneByEid($data['eid']);
    foreach ($data['values'] as $key => $value) {
      $set = 'set' . ucfirst($key);
      $obj->$set($value);
    }
    $this->entityManagerInterface->persist($obj);
    $this->entityManagerInterface->flush();
  }
}
