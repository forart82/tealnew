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

  public function changeValues(array $repositorys, array $data)
  {

    $obj = $repositorys[$data['entity']]->findOneByEid($data['eid']);

    foreach ($data['values'] as $key => $value) {
      if (preg_match('/inClass/', $key)) {
        $entity = ucfirst(str_replace(' inClass', '', $key));
        $method='get'.$entity;
        $repository = $repositorys[$entity]->findOneById($obj->$method()->getId());
        $dump="hallasdfsdfgqwerqwejkfhaöiksdjfhasodkjfhöoaweirujfwqöoafndvskfnbvglksdfjgnblioaewrjhföasdnfvksndfbgvöksdajföloaky";
        $dump=gzcompress($dump);
        dump($dump);
        $dump=gzuncompress($dump);
        dump($dump);
      }
    }
    //   dump($entity,$data);
    //   // $set = 'set' . ucfirst($key);
    //   // $obj->$set($value);
    // }
    // $this->entityManagerInterface->persist($obj);
    // $this->entityManagerInterface->flush();
  }
}
