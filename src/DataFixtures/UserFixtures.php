<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\CompanyRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    private $passwordEncoder;
    private $companyRepository;
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        CompanyRepository $companyRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->companyRepository = $companyRepository;
    }
    public function load(ObjectManager $manager)
    {

        $person = [
            'email' => [
                "teal.innovation.consulting@gmail.com",
                "timothee@esprit-teal.com",
                "carmelo.roberto.82@gmail.com",
                "emmanuelle.buono@hotmail.fr",
                "cyrille.stammler@gmail.com",
            ],
            'prenom' => [
                "Timothée",
                "Timothée",
                "Carmelo",
                "Emmanuelle",
                "Cyrille"
            ],
            'nom' => [
                "Couchoud",
                "Couchoud",
                "Roberto",
                "Bouno",
                "Stammler"
            ]
        ];
        $count = count($person["email"]);
        for ($i = 0; $i < $count; $i++) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword($user, '1234'));
            $user->setFirstname($person["prenom"][$i]);
            $user->setLastname($person["nom"][$i]);
            $user->setLanguage("fr");
            $user->setIsNew(1);
            $user->setRoles(['ROLE_SUPER_ADMIN']);
            $user->setCompany($this->companyRepository->findOneById(1));
            $user->setEmail($person["email"][$i]);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 9;
    }
}
