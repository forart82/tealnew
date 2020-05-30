<?php

/**
 * @file
 * Contains class wich will return a base64_encode string.
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;

use Twig\TwigFunction;

class AsImage extends AbstractExtension
{

  public $entityManagerInterface;

  public function __construct(EntityManagerInterface $entityManagerInterface)
  {
    $this->entityManagerInterface = $entityManagerInterface;
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('as_image', [$this, 'asImage'])
    ];
  }

  public function asImage($property)
  {
    return base64_encode(stream_get_contents($property));
  }

}