<?php

/**
 * @file
 * Contains a class to get key values as arry from an object
 */

namespace App\Twig;

use ReflectionClass;
use Doctrine\ORM\EntityManagerInterface;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

class CastToArray extends AbstractExtension
{
  private $entityManagerInterface;

  public function __construct(EntityManagerInterface $entityManagerInterface)
  {
    $this->entityManagerInterface=$entityManagerInterface;
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('cast_to_array', [$this, 'castToArray']),
    ];
  }
  /**
   * @param mixed $object
   */
  public function castToArray($object): array
  {
    $reflectionExtractor = new ReflectionExtractor();
    $doctrineExtractor = new DoctrineExtractor($this->entityManagerInterface);

    $propertyInfo = new PropertyInfoExtractor(
      [
        $reflectionExtractor,
        $doctrineExtractor
      ],
      [
        $doctrineExtractor,
        $reflectionExtractor
      ]
    );
    if (gettype($object) == "array") {
      $properties = $propertyInfo->getProperties("App\Entity\\" . (new ReflectionClass($object[0]))->getShortName());

    } else {
      $properties = $propertyInfo->getProperties("App\Entity\\" . (new ReflectionClass($object))->getShortName());
    }
    return $properties;
  }
}
