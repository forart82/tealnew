<?php

/**
 * @file
 * Contains a class to get key values as arry from an object
 */

namespace App\Twig;

use ReflectionClass;
use ReflectionProperty;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CastToArray extends AbstractExtension
{
  public function getFunctions():array
    {
    return [
      new TwigFunction('cast_to_array', [$this, 'castToArray']),
    ];
  }
  /**
   * @param mixed $object
   */
  public function castToArray($object):array
  {
    if(gettype($object)=="array")
    {
      $reflect = new ReflectionClass($object[0]);
    }
    else{
      $reflect = new ReflectionClass($object);
    }
    $props   = $reflect->getProperties();
    $names=[];
    foreach ($props as $prop) {
        $names[]= $prop->getName();
    }
    return $names;
  }
}
