<?php

namespace App\DataFixtures;

use App\Entity\Result;
use App\Repository\UserRepository;
use App\Repository\SubjectRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ResultFixtures extends Fixture implements OrderedFixtureInterface
{
    private $userRepository;
    private $subjectRepository;

    public function __construct(
        UserRepository $userRepository,
        SubjectRepository $subjectRepository
        )
    {
        $this->userRepository = $userRepository;
        $this->subjectRepository = $subjectRepository;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            for ($j = 1; $j < 41; $j++) {
                $result = new Result();
                $result->setChoice(mt_rand(0, 5))
                    ->setNotation(mt_rand(0, 5))
                    ->setIdSubject($this->subjectRepository->findOneById($j))
                    ->setIdUser($this->userRepository->findOneById($i));
                $manager->persist($result);
            }
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 8;
    }
}
