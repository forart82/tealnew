<?php

/**
 * @file
 * Contains a class to get type of elmement
 */

namespace App\Twig;

use ReflectionClass;
use Doctrine\ORM\EntityManagerInterface;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

class GetType extends AbstractExtension
{
  private $entityManagerInterface;

  public function __construct(EntityManagerInterface $entityManagerInterface)
  {
    $this->entityManagerInterface=$entityManagerInterface;
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('get_type', [$this, 'getType']),
    ];
  }
  /**
   * @param mixed $element
   */
  public function getType($object,$property)
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
      $properties = $propertyInfo->getTypes("App\Entity\\" . (new ReflectionClass($object[0]))->getShortName(),$property);

    } else {
      $properties = $propertyInfo->getTypes("App\Entity\\" . (new ReflectionClass($object))->getShortName(),$property);
    }
    return $properties[0]->getBuiltinType();
  }
}
