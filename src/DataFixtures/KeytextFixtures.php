<?php

namespace App\DataFixtures;

use App\Entity\Keytext;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KeytextFixtures extends Fixture implements OrderedFixtureInterface
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
        $fileKeytext = $csvDirectory . '/keytext.csv';
        $handle = fopen($fileKeytext, "r");
        $fileKeytext = fgetcsv($handle, 10000, ",");
        while ($fileKeytext) {
            $keytext = new Keytext();
            $keytext->setKeytext($fileKeytext[1]);
            $manager->persist($keytext);
            $fileKeytext = fgetcsv($handle, 10000, ",");
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
