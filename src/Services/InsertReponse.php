<?php

namespace App\Services;

use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;

class InsertReponse
{
    public function insert(Result $result, EntityManagerInterface $manager, $choice, $subjectId, $user)
    {
        $result->setChoice($choice);
        $result->setSubject($subjectId);
        $result->setUser($user);
        $result->setNotation($result->getNotation());
        $manager->persist($result);
        $manager->flush();
    }
    public function update(Result $result, EntityManagerInterface $manager, $choice, $subjectId)
    {
        $result->setChoice($choice);
        $manager->persist($result);
        $manager->flush();
    }
}
