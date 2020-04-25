<?php

namespace App\DataFixtures;

use App\Entity\Navigations;
use App\Repository\ImagesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class NavigationsFixtures extends Fixture implements OrderedFixtureInterface
{


    private $imagesRepository;

    public function __construct(
        ImagesRepository $imagesRepository
    ) {
        $this->imagesRepository = $imagesRepository;
    }
    public function load(ObjectManager $manager)
    {
        $navigations = [
            'name' => [
                // Menu Principal
                'settings',
                'results',
                // Menu Paramèters
                'introduction',
                'profil',
                'user',
                'client',
                'subject',
                'translation',
                'company',
                'email',
                'image',
                'users',
                'csv',
                'logout',
                // Menu Résultats
                'result',
                'notation',
                // Menu special
                'burger',
            ],
            'translation' => [
                // Menu Principal
                "{{'nav_settings'|trans|raw }}",
                "{{'nav_results'|trans|raw }}",
                // Menu Paramèters
                "{{'menu_intro'|trans|raw }}",
                "{{'menu_my_profile'|trans|raw }}",
                "{{'menu_user_management'|trans|raw }}",
                "{{'menu_create_client_account'|trans|raw }}",
                "{{'menu_subject_management'|trans|raw }}",
                "{{'menu_translation_management'|trans|raw }}",
                "{{'menu_company_management'|trans|raw }}",
                "{{'menu_email_management'|trans|raw }}",
                "{{'menu_image_management'|trans|raw }}",
                "{{'menu_list_users'|trans|raw }}",
                "{{'menu_csv_key_values'|trans|raw }}",
                "{{'menu_logout'|trans|raw }}",
                // Menu Résultats
                "{{'result_title'|trans|raw }}",
                "{{'notation_title'|trans|raw }}",
                // Menu special
                "",
            ],
            'link' => [
                // Menu Principal
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                // Menu Paramèters
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                // Menu Résultats
                "{{path('introduction')}}",
                "{{path('introduction')}}",
                // Menu special
                "",
            ],
            'positions' => [
                // Menu Principal
                1000,
                2000,
                // Menu Paramèters
                1010,
                1011,
                1012,
                1013,
                1014,
                1015,
                1016,
                1017,
                1018,
                1019,
                1020,
                1021,
                // Menu Résultats
                2020,
                2021,
                // Menu special
                9999,
            ],
            'subPositions' => [
                // Menu Principal
                1000,
                2000,
                // Menu Paramèters
                1010,
                1011,
                1012,
                1013,
                1014,
                1015,
                1016,
                1017,
                1018,
                1019,
                1020,
                1021,
                // Menu Résultats
                2010,
                2021,
                // Menu special
                9999,
            ],
            'authorisation' => [
                // Menu Principal
                'uas',
                'uas',
                // Menu Paramèters
                'uas',
                'uas',
                'as',
                's',
                's',
                's',
                's',
                's',
                's',
                's',
                's',
                'uas',
                // Menu Résultats
                'uas',
                'uas',
                // Menu special
                'uas',
            ]
        ];
        for ($i = 0; $i < 17; $i++) {
            $navigation = new Navigations();
            $navigation->setName($navigations['name'][$i]);
            $navigation->setTranslation($navigations['translation'][$i]);
            $navigation->setLink($navigations['link'][$i]);
            $navigation->setPosition($navigations['positions'][$i]);
            if ($image = $this->imagesRepository->findOneByName('site' . $navigations['name'][$i])) {
                $navigation->setImage($this->imagesRepository->findOneByName($image));
            } else {
                $navigation->setImage($this->imagesRepository->findOneByName('site_burger'));
            }
            if ($i > 1) {
                $navigation->setSubPosition($navigations['subPositions'][$i]);
            }
            $navigation->setAuthorisation($navigations['authorisation'][$i]);
            $manager->persist($navigation);
        }
        $manager->flush();
    }


    public function getOrder()
    {
        return 20;
    }
}
