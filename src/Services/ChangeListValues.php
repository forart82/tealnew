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
        $entity = $repository->findOneByEid($data['eid']);
        $setProperty = 'set' . ucfirst($data['property']);
        $getProperty = 'get' . ucfirst($data['property']);
        $entity->$setProperty(preg_filter('/(<div>|</div>|<br>|</br>)/', '', $data['value']));
        dump($entity->getProperty());
        $this->entityManagerInterface->persist($entity);
        $this->entityManagerInterface->flush();
    }
}
