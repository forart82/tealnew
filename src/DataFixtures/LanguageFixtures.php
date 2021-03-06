<?php

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LanguageFixtures extends Fixture implements OrderedFixtureInterface
{

    private $container;
    public function __construct(

        ContainerInterface $container = null
    ) {

        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $csvDirectory = $this->container->getParameter('csvs_directory');
        $fileLanguage = $csvDirectory . '/language.csv';
        $handle = fopen($fileLanguage, "r");
        $fileLanguage = fgetcsv($handle, 10000, ",");
        while ($fileLanguage) {
            $language = new Language();
            $language->setCode($fileLanguage[1])
                ->setDenomination($fileLanguage[2])
                ->setActive($fileLanguage[3]);
            $manager->persist($language);
            $fileLanguage = fgetcsv($handle, 10000, ",");
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}
