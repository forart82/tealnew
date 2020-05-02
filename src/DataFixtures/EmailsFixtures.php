<?php

namespace App\DataFixtures;

use App\Entity\Emails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class EmailsFixtures extends Fixture implements OrderedFixtureInterface
{

  public function load(ObjectManager $manager)
  {

    $email = new Emails();
    $email->setMessage('Votre message doit contenir le code suivant: {prenom}, {nom} et {lien}!');
    $email->setLanguage('fr');
    $manager->persist($email);
    $email = new Emails();
    $email->setMessage('Your message must contain the following code: {prenom}, {nom} et {lien}!');
    $email->setLanguage('en');
    $manager->persist($email);
    $manager->flush();
  }

  public function getOrder()
  {
    return 3;
  }
}
