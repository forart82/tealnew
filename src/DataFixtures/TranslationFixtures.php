<?php

namespace App\DataFixtures;

use App\Entity\Translation;
use App\Repository\KeytextRepository;
use App\Repository\LanguageRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TranslationFixtures extends Fixture implements OrderedFixtureInterface
{
    private $languageRepository;
    private $keytextRepository;
    private $container;
    public function __construct(
        LanguageRepository $languageRepository,
        KeytextRepository $keytextRepository,
        ContainerInterface $container = null
    ) {
        $this->languageRepository = $languageRepository;
        $this->keytextRepository = $keytextRepository;
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $csvDirectory = $this->container->getParameter('csvs_directory');
        $fileTranslation = $csvDirectory . '/translation.csv';
        $handle = fopen($fileTranslation, "r");
        $fileTranslation = fgetcsv($handle, 10000, ",");
        while ($fileTranslation) {
            $translation = new Translation();
            $translation->setKeytext($this->keytextRepository->findOneById($fileTranslation[2]))
                ->setLanguage($this->languageRepository->findOneById($fileTranslation[1]))
                ->setText($fileTranslation[3]);
            $manager->persist($translation);
            $fileTranslation = fgetcsv($handle, 10000, ",");
        }
        $manager->flush();
    }
    public function getOrder()
    {
        return 6;
    }
}
