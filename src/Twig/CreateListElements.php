<?php

/**
 * @file
 * Contains a class who create the element for _list.html.twig
 */

namespace App\Twig;

use ReflectionClass;
use Doctrine\ORM\EntityManagerInterface;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

class CreateListElements extends AbstractExtension
{
  private $entityManagerInterface;
  private $reflectionExtractor;
  private $doctrineExtractor;
  private $propertyInfo;

  public function __construct(EntityManagerInterface $entityManagerInterface)
  {
    $this->entityManagerInterface = $entityManagerInterface;
    $this->reflectionExtractor = new ReflectionExtractor();
    $this->doctrineExtractor = new DoctrineExtractor($this->entityManagerInterface);

    $this->propertyInfo = new PropertyInfoExtractor(
      [
        $this->reflectionExtractor,
        $this->doctrineExtractor
      ],
      [
        $this->doctrineExtractor,
        $this->reflectionExtractor
      ]
    );
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('create_elements', [$this, 'createElements']),
    ];
  }
  /**
   * @param mixed $elements
   */
  public function createElements($object)
  {

    $entity = get_class($object);
    $properties = $this->propertyInfo->getProperties($entity);
    $elements = [];
    $this->getType($entity, $properties, $elements);
    dump($elements);
    return false;
  }

  /**
   * @param mixed $elements
   */
  public function getType($entity, $properties, &$elements)
  {
    foreach ($properties as $key=> $property) {
      $info = $this->propertyInfo->getTypes($entity, $property);
      if ($info) {
        if ($info[0]->getClassName()) {
          $inEntity = $info[0]->getClassName();
          $inProperties = $this->propertyInfo->getProperties($inEntity);
          $this->getType($inEntity, $inProperties, $elements);
        }
        dump($key);
        $elements[$entity][$info[0]->getBuiltinType()] = $property;
      }
    }
  }
}
