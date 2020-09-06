<?php

namespace App\Services;


use App\Entity\Result;
use App\Entity\User;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateResults
{

    private $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function create(
        EntityManagerInterface $entityManagerInterface,
        User $user
    ) {
        for ($j = 1; $j < 41; $j++) {
            $result = new Result();
            $result->setChoice(0)
                ->setNotation(0)
                ->setSubject($this->subjectRepository->findOneById($j))
                ->setUser($user);
            $entityManagerInterface->persist($result);
        }
        $entityManagerInterface->flush();
    }
}
