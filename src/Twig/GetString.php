<?php

/**
 * @file
 * Contains class who will check is type a string
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;

use Twig\TwigFunction;

class GetString extends AbstractExtension
{

  public $entityManagerInterface;

  public function __construct(EntityManagerInterface $entityManagerInterface)
  {
    $this->entityManagerInterface = $entityManagerInterface;
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('get_string', [$this, 'getString'])
    ];
  }

  public function getString($object, $property, $elements = 3, $separateur = '<br>'): string
  {
    return $this->checkProperty($object, $property, $elements, $separateur);
  }

  public function checkProperty($object, $property, $elements, $separateur): string
  {
    $string = "";
    $property = 'get' . ucfirst($property);
    switch ($property) {
      case 'getUsers':
        $users = $object->$property();
        foreach ($users as $key => $user) {
          if ($key > $elements) {
            $string .= '...';
            break;
          }
          $string .= $user->getFirstName() . $separateur;
        }
        break;
      case 'getLogo':
        $string = base64_encode(stream_get_contents($object->$property()));
        break;
      default:
        $string = $object->$property();
        break;
    }
    return (string) $string;
  }
}
