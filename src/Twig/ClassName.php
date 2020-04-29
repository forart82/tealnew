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
   public function getFunctions()
   {
     return [
       new TwigFunction('className',[$this, 'getClassName'])
     ];
   }

   public function getClassName($classObject)
   {
     return (new \ReflectionClass($classObject))->getShortName();
   }
 }
