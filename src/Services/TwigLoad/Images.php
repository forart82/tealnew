<?php

namespace App\Services\TwigLoad;

use App\Repository\ImagesRepository;

class Images
{
    /**
     * @var \App\Entity\Images[] $site
     */
    public  $site;
    /**
     * @var \App\Entity\Images[] $questions
     */
    public  $questions;
    /**
     * @var \App\Entity\Images[] $answers
     */
    public  $answers;

    public function __construct(ImagesRepository $imagesRepository)
    {
        $this->site = $imagesRepository->findByCategory('site');
        $this->questions = $imagesRepository->findByCategory('questions');
        $this->answers = $imagesRepository->findByCategory('answers');
    }
}
