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
    // dump($property);
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
      case 'getNavigations':
        // TODO navigation has problem with Doctrine\ORM\PersistentCollection

        // $navigations=$object->getNavigations();
        // if(gettype($navigations)=="array")
        // {
        //   foreach ($navigations as $key => $navigation) {
        //     if ($key > $elements) {
        //       $string .= '...';
        //       break;
        //     }
        //     $string .= $navigation->getName() . $separateur;
        //   }
        // }
        // else{
        //   $string=$navigations->getName();
        // }
        break;
      case 'getTranslation':
        // TODO navigation has problem with Doctrine\ORM\PersistentCollection

        // $translations = $object->$property();
        // if (gettype($translations) == "array") {
        //   foreach ($translations as $key => $translation) {
        //     if ($key > $elements) {
        //       $string .= '...';
        //       break;
        //     }
        //     $string .= $translation->getId() . $separateur;
        //   }
        // } else {
        //   $string = $translations->getId();
        // }
        break;
      case 'getTranslations':
        // TODO navigation has problem with Doctrine\ORM\PersistentCollection

        // $translations = $object->$property();
        // if (gettype($translations) == "array") {
        //   foreach ($translations as $key => $translation) {
        //     if ($key > $elements) {
        //       $string .= '...';
        //       break;
        //     }
        //     $string .= $translation->getId() . $separateur;
        //   }
        // } else {
        //   $string = $translations->getId();
        // }
        break;
      case 'getImage':
        // TODO navigation has problem with Doctrine\ORM\PersistentCollection
        $svgs = $object->$property();
        if (gettype($svgs) == "array") {
          foreach ($svgs as $key => $svg) {
            if ($key > $elements) {
              $string .= '...';
              break;
            }
            $string .= $svg->getId() . $separateur;
          }
        } else {
          $string = $svgs->getId();
        }
        break;
      case 'getRoles':
        $string .= $object->$property()[0];
        break;
      case 'getUserResult':
        // TODO navigation has problem with Doctrine\ORM\PersistentCollection
        // $results = $object->$property();
        // if (gettype($results) == "array") {
        //   foreach ($results as $key => $image) {
        //     if ($key > $elements) {
        //       $string .= '...';
        //       break;
        //     }
        //     $string .= $image->getId() . $separateur;
        //   }
        // } else {
        //   $string = $results->getId();
        // }
        break;
        case 'getSubjectResult':
          // TODO navigation has problem with Doctrine\ORM\PersistentCollection
          // $results = $object->$property();
          // if (gettype($results) == "array") {
          //   foreach ($results as $key => $image) {
          //     if ($key > $elements) {
          //       $string .= '...';
          //       break;
          //     }
          //     $string .= $image->getId() . $separateur;
          //   }
          // } else {
          //   $string = $results->getId();
          // }
          break;
        case 'getCompany':
          $string.= $object->$property()->getName();
          break;
      default:
        $string = $object->$property();
        break;
    }
    return (string) $string;
  }
}
