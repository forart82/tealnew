<?php

namespace App\Services\TwigLoad;

use App\Repository\NavigationsRepository;
use App\Repository\SubjectRepository;
use App\Repository\LanguageRepository;
use App\Services\CheckLanguage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Navigation
{

    /**
     * @var \App\Entity\Navigations[] $navigation
     */
    public  $navigation;

    /**
     * @var \App\Entity\Subject[] $subjects
     */
    public $subjects;

    private $languageRepository;
    private $parameterBagInterface;

    public function __construct(
        NavigationsRepository $navigationsRepository,
        SubjectRepository $subjectRepository,
        LanguageRepository $languageRepository,
        ParameterBagInterface $parameterBagInterface
        )
    {
        $this->languageRepository=$languageRepository;
        $this->parameterBagInterface=$parameterBagInterface;
        $language=new CheckLanguage($languageRepository,$parameterBagInterface);
        $this->subjects=$subjectRepository->findByLanguage($language->doLangue());
        $this->navigation = $navigationsRepository->findAll();
    }
}
