<?php

namespace App\Services\TwigLoad;

use App\Repository\ImagesRepository;

class Images
{

    public  $images;

    public function __construct(ImagesRepository $imagesRepository)
    {
        $this->images = $imagesRepository->findByCategory('site');
    }
}
