<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * @file
 * Contains a class to collect errors and show messages.
 */

class ReportAndMessage
{
  private $session;
  private $report;
  private $messages;
  private $messageType;
  private $message;

  public function __construct(array &$messageType, array &$message)
  {
    $this->session = new Session();
    $this->session->set('name', 'ReportAndMessage');
    $this->report = [];
    $this->messages = [];
    $this->messageType = &$messageType;
    $this->message = &$message;
  }


  public function reportRecord(
    string $functionName = "",
    bool $functionReturnValue = false
  ): void {
    $this->report[$functionName] = $functionReturnValue;
    $this->messages[$functionName] = $functionReturnValue;
    foreach($this->message as $key => $message)
    {
      $this->session->getFlashBag()->add($this->messageType[$key], $message);
    }
    $this->clearMessage();
  }

  public function getReport():array
  {
    return $this->report;
  }

  public function clearReport():void
  {
    $this->report = [];
  }

  public function getMessage():array
  {
    return $this->message;
  }

  public function clearMessage():void
  {
    $this->message = [];
    $this->messageType =[];
  }

  public function clearReportAndMessage():void
  {
    $this->clearReport();
    $this->clearMessage();
  }

  public function getSessionFlashbag():FlashBagInterface
  {
    return $this->session->getFlashBag();
  }
}
