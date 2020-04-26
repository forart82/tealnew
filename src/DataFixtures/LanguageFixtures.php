<?php

namespace App\DataFixtures;

use App\Entity\Emails;
use App\Entity\Keytext;
use App\Entity\Language;
use App\Entity\Translation;
use App\Repository\KeytextRepository;
use App\Repository\LanguageRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LanguageFixtures extends Fixture implements OrderedFixtureInterface
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
        $fileKeytext = $csvDirectory . '/keytext.csv';
        $fileLanguage = $csvDirectory . '/language.csv';
        $fileTranslation = $csvDirectory . '/translation.csv';
        $email = new Emails();
        $email->setMessage('Votre message doit contenir le code suivant: {prenom}, {nom} et {lien}!');
        $email->setLanguage('fr');
        $manager->persist($email);
        $email = new Emails();
        $email->setMessage('Your message must contain the following code: {prenom}, {nom} et {lien}!');
        $email->setLanguage('en');
        $manager->persist($email);
        $manager->flush();
        $handle = fopen($fileKeytext, "r");
        $fileKeytext = fgetcsv($handle, 10000, ",");
        while ($fileKeytext) {
            $keytext = new Keytext();
            $keytext->setKeytext($fileKeytext[1]);
            $manager->persist($keytext);
            $fileKeytext = fgetcsv($handle, 10000, ",");
        }
        $manager->flush();
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
        return 3;
    }
}
