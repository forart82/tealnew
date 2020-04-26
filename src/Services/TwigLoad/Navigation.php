<?php

namespace App\Services\TwigLoad;

use App\Repository\NavigationsRepository;

class Navigation
{

    /**
     * @var \App\Entity\Navigations[] $navigation
     */
    public  $navigation;

    public function __construct(NavigationsRepository $navigationsRepository)
    {
        $this->navigation = $navigationsRepository->findAll();
    }
}
