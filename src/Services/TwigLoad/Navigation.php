<?php

namespace App\Services\TwigLoad;

use App\Repository\NavigationsRepository;

class Navigation
{

    public  $navigation;

    public function __construct(NavigationsRepository $navigationsRepository)
    {
        $this->navigation = $navigationsRepository->findAll();
    }
}
