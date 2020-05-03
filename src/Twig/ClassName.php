<?php

/**
 * @file
 * Contains twig extendet class to return name of class object.
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ClassName extends AbstractExtension
{
  public function getFunctions(): array
  {
    return [
      new TwigFunction('className', [$this, 'getClassName'])
    ];
  }
  /**
   * @param mixed $classObject
   */
  public function getClassName($classObject): string
  {
    if (gettype($classObject) == "array") {
      return (new \ReflectionClass($classObject[0]))->getShortName();
    }
    return (new \ReflectionClass($classObject))->getShortName();
  }
}
