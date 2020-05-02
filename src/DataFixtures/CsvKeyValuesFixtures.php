<?php

namespace App\DataFixtures;

use App\Entity\CsvKeyValues;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CsvKeyValuesFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $csvKeyValuesArray = [
            'name' => [
                'Firstname',
                'Prenom',
                'Lastname',
                'Nom',
                'Email',
            ],
            'asValue' => [
                'firstname',
                'firstname',
                'lastname',
                'lastname',
                'email',
            ],
            'type' => [
                'alpha',
                'alpha',
                'alpha',
                'alpha',
                'email',
            ],
        ];

        for($i=0;$i<5;$i++)
        {
            $csvKeyValues = new CsvKeyValues();
            $csvKeyValues->setName($csvKeyValuesArray['name'][$i]);
            $csvKeyValues->setasValue($csvKeyValuesArray['asValue'][$i]);
            $csvKeyValues->setType($csvKeyValuesArray['type'][$i]);
            $manager->persist($csvKeyValues);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
