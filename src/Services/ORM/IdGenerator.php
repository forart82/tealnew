<?php

/**
 * @file
 * Contains class to create radndom id for doctrine database.
 */

namespace App\Services\ORM;

use App\Services\Statics\UniqueId;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

/**
 * {@inheritDoc}
 */

class IdGenerator extends AbstractIdGenerator
{
  /**
   * {@inheritDoc}
   */
  public function generate(EntityManager $em, $entity):string
  {
    return UniqueId::createId();
  }
}
