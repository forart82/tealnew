<?php

/**
 * @file
 * Contains class to test resultat of ResultsDiagram.php
 */

namespace App\Test\Services;

use App\Entity\User;
use App\Entity\Result;
use App\Repository\ResultRepository;
use App\Repository\UserRepository;
use App\Services\ResultsDiagram;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResultsDiagramTest extends KernelTestCase
{


  /**
   * @var \Doctrine\ORM\EntityManager
   */
  private $entityManager;

  /**
   * {@inheritDoc}
   */
  protected function setUp()
  {

    $kernel = self::bootKernel();

    $this->entityManager = $kernel->getContainer()
      ->get('doctrine')
      ->getManager();
  }
  public function testDoDiagram()
  {

    $this->result = $this->entityManager->getRepository(Result::class);

    $this->user = $this->entityManager->getRepository(User::class)
      ->findOneBy(['id'=>1]);
    $diagram = new ResultsDiagram($this->result, $this->user);
    $diagram = $diagram->doDiagram();
    foreach($diagram as $point)
    {
      $this->assertSame(255,(int)$point['offsetX'
      ]);
    }
  }

}
