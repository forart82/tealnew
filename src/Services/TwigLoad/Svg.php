<?php

namespace App\Services\TwigLoad;

use App\Repository\SvgRepository;

class Svg
{
    /**
     * @var \App\Entity\Svg[] $site
     */
    public  $site;
    /**
     * @var \App\Entity\Svg[] $questions
     */
    public  $questions;
    /**
     * @var \App\Entity\Svg[] $answers
     */
    public  $answers;

    public function __construct(SvgRepository $svgRepository)
    {
        $this->site = $svgRepository->findByCategory('site');
        $this->questions = $svgRepository->findByCategory('questions');
        $this->answers = $svgRepository->findByCategory('answers');
    }
}
