<?php

/**
 * @file
 * Contains a class to create a unique string base sur une timestamp
 */

namespace App\Services\Statics;

class UniqueId
{
  public static function createId()
  {
    $uniqueId=microtime(true).'O' . uniqid(). uniqid();
    return str_replace('.','',$uniqueId);
  }
}
