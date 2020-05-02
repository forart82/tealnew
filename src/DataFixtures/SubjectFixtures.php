<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use App\Repository\SvgRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SubjectFixtures extends Fixture implements OrderedFixtureInterface
{
    private $svgRepository;
    private $container;
    public function __construct(
        SvgRepository $svgRepository,
        ContainerInterface $container = null
        )
    {
        $this->svgRepository = $svgRepository;
        $this->container = $container;

    }
    public function load(ObjectManager $manager)
    {
        $fileCsv = $this->container->getParameter('csvs_directory') . '/questions.csv';
        $handle = fopen($fileCsv, "r");
        $counter = 0;
        for ($j = 1; $j < 41; $j++) {
            $svg = $this->svgRepository->findOneByNameAndDirectory('questions_'.++$counter, "questions");
            if ($counter == 20) {
                $counter = 0;
            }
            $subject = new Subject();
            $sujet = fgetcsv($handle, 10000, ",");
            $subject->setQuestion($sujet[2])
                ->setAnswerOne($sujet[3])
                ->setAnswerTwo($sujet[4])
                ->setAnswerThree($sujet[5])
                ->setAnswerFour($sujet[6])
                ->setAnswerFive($sujet[7])
                ->setPosition($sujet[8])
                ->setUserNotation(3)
                ->setSvg($svg)
                ->setTitle($sujet[10])
                ->setIsRespond(0)
                ->setLanguage($sujet[12]);
            $manager->persist($subject);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
