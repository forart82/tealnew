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

    public function changeValues(array $data)
    {
        $class='\\App\\Entity\\'.$data['entity'];
        $repository=$this->entityManagerInterface->getRepository($class);
        $entity = $repository->findOneBy(['eid'=>$data['eid']]);
        $setProperty = 'set' . ucfirst($data['property']);
        $entity->$setProperty(preg_replace('/(<div>|<\/div>|<br>|<\/br>)/', '', $data['value']));
        $this->entityManagerInterface->persist($entity);
        $this->entityManagerInterface->flush();
    }
}
