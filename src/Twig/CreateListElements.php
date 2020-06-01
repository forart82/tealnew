<?php

/**
 * @file
 * Contains a class who create the element for _list.html.twig
 */

namespace App\Twig;

use Doctrine\ORM\EntityManagerInterface;
use App\Services\Statics\Entities;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CreateListElements extends AbstractExtension
{
    private $em;
    private $reflectionExtractor;
    private $doctrineExtractor;
    private $propertyInfo;
    private $container;

    public function __construct(
        EntityManagerInterface $em,
        ContainerInterface $container
    ) {
        $this->em = $em;
        $this->reflectionExtractor = new ReflectionExtractor();
        $this->doctrineExtractor = new DoctrineExtractor($this->em);
        $this->propertyInfo = new PropertyInfoExtractor(
            [
                $this->reflectionExtractor,
                $this->doctrineExtractor,
            ],
            [
                $this->doctrineExtractor,
                $this->reflectionExtractor,
            ]
        );

        $this->container = $container;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('create_elements', [$this, 'createElements']),
        ];
    }
    /**
     * @param array
     */
    public function createElements($object, $id)
    {
        $entity = get_class($object);

        $properties = $this->propertyInfo->getProperties($entity);
        $elements = [];
        $this->getType($entity, $properties, $elements, $id);
        $this->getValues($elements);

        return $elements;
    }

    public function getType($entity, $properties, &$elements, $id)
    {
        foreach ($properties as $key => $property) {
            $info = $this->propertyInfo->getTypes($entity, $property);
            if ($info) {
                if ($info[0]->getClassName()) {
                    $inEntity = $info[0]->getClassName();
                    $inProperties = $this->propertyInfo->getProperties($inEntity);
                    $this->getType($inEntity, $inProperties, $elements, $id);
                }
                if (
                    $info[0]->getBuiltinType() != 'object' && in_array($entity, Entities::ENTITIES)
                ) {
                    $elements[$id][$entity][$property] = ['type' => $info[0]->getBuiltinType()];
                }
            }
        }
    }

    public function getValues(&$elements)
    {
        $temp = $elements;
        $class = null;
        foreach ($elements as $id => $entities) {
            foreach ($entities as $entity => $properties) {
                foreach ($properties as $property => $type) {
                    if (!$class) {
                        $class = $entity;
                    }
                    if ($class != $entity) {
                        $getEntity = 'get' . substr($entity, 11);
                        $getProperty = 'get' . ucFirst($property);
                        $repo = $this->em->getRepository($class)->findOneBy(['id' => $id]);
                        $temp[$id][$entity][$property] += ['value' => $repo->$getEntity()->$getProperty()];
                    } else {
                        $getProperty = 'get' . ucFirst($property);
                        $repo = $this->em->getRepository($class)->findOneBy(['id' => $id]);
                        $temp[$id][$entity][$property] += ['value' => $repo->$getProperty()];
                    }
                }
            }
        }
        $elements = $temp;
    }
}
