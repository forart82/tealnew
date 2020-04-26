<?php

namespace App\Services;

use App\Repository\LanguageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CheckLanguage extends AbstractController
{
    private $languageRepository;
    private $parameterBagInterface;
    private $available;

    public function __construct(
        LanguageRepository $languageRepository,
        ParameterBagInterface $parameterBagInterface
        )
    {
        $this->languageRepository=$languageRepository;
        $this->parameterBagInterface=$parameterBagInterface;
        $this->available = [];
    }

    public function doLangue(): string
    {
        $this->getAvailableLangues();
        $language=$this->compareLangue();
        return '/' . $language . '/introduction';
    }

    public function getAvailableLangues(): void
    {
        $languages = $this->languageRepository->findAll();
        foreach ($languages as $language) {
            $this->available[] = $language->getCode();
        }
    }

    public function compareLangue(): string
    {
        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            if (empty($this->available)) {
                return $langs[0];
            }
            foreach ($langs as $lang) {
                $lang = substr($lang, 0, 2);
                if (in_Array($lang, $this->available)) {
                    return $lang;
                }
            }
        }
        return $this->parameterBagInterface->get('locale');
    }

}
