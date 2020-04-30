<?php

namespace App\Services;

use App\Entity\Result;
use App\Repository\ResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class InsertReponse
{
    private $resultRepository;
    public function __construct(ResultRepository $resultRepository)
    {
        $this->resultRepository = $resultRepository;
    }
    public function insert(Result $result, EntityManagerInterface $manager, $choice, $subjectId, $user)
    {
        $result->setChoice($choice);
        $result->setIdSubject($subjectId);
        $result->setIdUser($user);
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
