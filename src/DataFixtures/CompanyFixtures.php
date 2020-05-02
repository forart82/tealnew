<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CompanyFixtures extends Fixture implements OrderedFixtureInterface
{
    private $container;

    public function __construct(
        ContainerInterface $container = null
    )
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $logo = $this->container->getParameter('images_directory') . '/logos/default_logo.png';
        $company = new Company();
        $company->setName("TealFinder")
            ->setMatricule("addressmail_name")
            ->setLogo(file_get_contents($logo))
            ->setLanguage('fr');
        $manager->persist($company);
        $manager->flush();
    }

    public function getOrder()
    {
        return 8;
    }
}
