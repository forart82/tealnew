<?php

/**
 * @file
 * Contains a class to get key values as arry from an object
 */

namespace App\Twig;

use ReflectionClass;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CastToArray extends AbstractExtension
{
  public function getFunctions()
    {
    return [
      new TwigFunction('cast_to_array', [$this, 'castToArray']),
    ];
  }

  public function castToArray($object)
  {
    $properties="";
    $object = new ReflectionClass($object[0]);
    $reflect=$object->getProperties();
    foreach($reflect as $key => $name);
    {
      dump($name);
      $properties.= $name->getName();
    }
    dump($properties, $object->getProperties());
    // return [];
  }
}
