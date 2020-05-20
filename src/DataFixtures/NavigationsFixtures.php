<?php

namespace App\DataFixtures;

use App\Entity\Navigations;
use App\Repository\SvgRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class NavigationsFixtures extends Fixture implements OrderedFixtureInterface
{


    private $svgRepository;

    public function __construct(
        SvgRepository $svgRepository
    ) {
        $this->svgRepository = $svgRepository;
    }
    public function load(ObjectManager $manager)
    {
        $navigations = [
            'name' => [
                // Menu Principal
                'settings',
                'results',
                // Menu Paramètersintroduction
                'intro',
                'profil',
                'user',
                'client',
                'subject',
                'translation',
                'company',
                'email',
                'svg',
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
                "nav_settings",
                "nav_results",
                // Menu Paramèters
                "menu_intro",
                "menu_my_profile",
                "menu_user_management",
                "menu_create_client_account",
                "menu_subject_management",
                "menu_translation_management",
                "menu_company_management",
                "menu_email_management",
                "menu_svg_management",
                "menu_list_users",
                "menu_csv_key_values",
                "menu_logout",
                // Menu Résultats
                "result_title",
                "notation_title",
                // Menu special
                "",
            ],
            'link' => [
                // Menu Principal
                "introduction",
                "introduction",
                // Menu Paramèters
                "introduction",
                "introduction",
                "introduction",
                "introduction",
                "subject",
                "translation",
                "company",
                "emails",
                "svg",
                "user",
                "csvkeyvalues",
                "app_logout",
                // Menu Résultats
                "introduction",
                "introduction",
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
            if ($svg = $this->svgRepository->findOneByName('site' .'_'. $navigations['name'][$i])) {
                $navigation->setSvg($svg);
            } else {
                $navigation->setSvg($this->svgRepository->findOneByName('site_burger'));
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
        return 2;
    }
}
