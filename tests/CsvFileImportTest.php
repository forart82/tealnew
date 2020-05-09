<?php

/**
 * @files
 * Contains a clas to test all function of CsvFileImport.php
 */

namespace App\Tests\Services;

use App\Entity\User;
use App\Entity\CsvKeyValues;
use App\Repository\CsvKeyValuesRepository;
use App\Services\CsvFileImport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Repository\UserRepository;
use App\Services\ReportAndMessage;
use App\Services\SendMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class CsvFileImportTest extends TestCase
{

  public function testCheckKeyValuesEmpty()
  {
    $csvKeyValuesOne=new CsvKeyValues();
    $csvKeyValuesOne->setName("name");
    $csvKeyValuesOne->setType("type");
    $csvKeyValuesOne->setasValue("value");

    $csvKeyValuesTwo = new CsvKeyValues();
    $csvKeyValuesTwo->setName("name");
    $csvKeyValuesTwo->setType("type");
    $csvKeyValuesTwo->setasValue("value");

    $csvKeyValuesRepository=$this->createMock(CsvKeyValuesRepository::class);
    $csvKeyValuesRepository->expects($this->any())
      ->method('find')
      ->willReturn([]);

    $objectManager = $this->createMock(ObjectManager::class);
    // use getMock() on PHPUnit 5.3 or below
    // $objectManager = $this->getMock(ObjectManager::class);
    $objectManager->expects($this->any())
      ->method('getRepository')
      ->willReturn($csvKeyValuesRepository);

    $userRepository = $this->createMock(UserRepository::class);
    $userPasswordEncoderInterface = $this->createMock(UserPasswordEncoderInterface::class);
    $entityManagerInterface = $this->createMock(EntityManagerInterface::class);
    $sendMailer = $this->createMock(SendMailer::class);
    $translatorInterface = $this->createMock(TranslatorInterface::class);
    $requestStack = $this->createMock(RequestStack::class);
    $csvFileImport=new CsvFileImport(
      null,
      $userRepository,
      $userPasswordEncoderInterface,
      $entityManagerInterface,
      $sendMailer,
      $translatorInterface,
      $requestStack
    );
    // $file = include()
    $handle = fopen('public/contents/uploads/test.csv', 'r');
    $data = fgetcsv($handle, 1000)[0];
    $csvFileImport->setFile($data);

    dd($handle);
    $this->assertTrue(true);
  }
}
