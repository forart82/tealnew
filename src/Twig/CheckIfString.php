<?php

/**
 * @file
 * Contains class who will check is type a string
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CheckIfString extends AbstractExtension
{
  public function getFunctions()
  {
    return [
      new TwigFunction('check_if_string', [$this, 'checkIfString'])
    ];

  }

  public function checkIfString($object,$property)
  {
  }
}
