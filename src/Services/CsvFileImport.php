<?php

/**
 * @file
 * Contains a class to handle csv file import.
 */

namespace App\Services;

use App\Entity\User;
use App\Repository\CsvKeyValuesRepository;
use App\Repository\UserRepository;
use App\Services\ReportAndMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CsvFileImport
{
  private $file;
  private $csvKeyValuesRepository;
  private $userRepository;
  private $user;
  private $userPasswordEncoderInterface;
  private $entityManagerInterface;
  private $csvKeyValues;
  private $csvKeyValuesName;
  private $csvKeyValuesValue;
  private $csvKeyValueTypes;
  private $csvKeyValuePositions;
  private $report;
  private $messageType;
  private $message;
  private $data;
  private $handle;
  private $handleIsOpen;
  private $rows;
  private $elements;
  private $emptyField;
  private $fields;
  private $functionToCallArray;
  private $errorTable;

  public function __construct(
    string $file,
    CsvKeyValuesRepository $csvKeyValuesRepository,
    UserRepository $userRepository,
    User $user,
    UserPasswordEncoderInterface $userPasswordEncoderInterface,
    EntityManagerInterface $entityManagerInterface
  ) {
    $this->file = $file;
    $this->csvKeyValuesRepository = $csvKeyValuesRepository;
    $this->userRepository = $userRepository;
    $this->user = $user;
    $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
    $this->entityManagerInterface = $entityManagerInterface;
    $this->csvKeyValues = $csvKeyValuesRepository->findAll();
    $this->csvKeyValuesName = array_map('strtolower', array_map('current', $csvKeyValuesRepository->findAllByName()));
    $this->csvKeyValuesValue = array_map('current', $csvKeyValuesRepository->findDistinctValue());
    $this->csvKeyValuePositions = [];
    $this->messageType = [];
    $this->message = [];
    $this->report = new ReportAndMessage($this->messageType, $this->message);
    $this->handleIsOpen = true;
    $this->handle = fopen($file, 'r');
    $this->data = fgetcsv($this->handle, 1000)[0];
    $this->columns = count(explode(';', $this->data));
    $this->rows = 0;
    $this->elements = 0;
    $this->emptyField = [];
    $this->fields = explode(';', $this->data);
    // Must be in reverse order!
    $this->functionToCallArray = [
      // 'usersuccesInformation',
      // 'sendMail',
      'saveUser',
      'createErrorTable',
      'checkEmailExist',
      'checkCsvKeyValueTypes',
      'checkEmptyField',
      'checkFieldQuantity',
    ]; // check ..
    $this->errorTable = [
      'fields' => [],
      'errors' => [],
    ];
  }

  public function doCsv()
  {
    // Check file type.
    $this->report->reportRecord("checkFileType", $this->checkFileType());

    // Check if file is empty.
    $this->report->reportRecord("checkFileEmpty", $this->checkFileEmpty());

    // Check if csv key name exist.
    $this->report->reportRecord("checkKeyValuesName", $this->checkKeyValuesName());

    // Check all values needed exist.
    $this->report->reportRecord("checkKeyValuesValue", $this->checkKeyValuesValue());

    // Set key values in all variables.
    $this->report->reportRecord('setKeyValues', $this->setKeyValues());

    if (in_array(false, $this->report->getReport())) {
      return false;
    }

    while ($this->doHandle()) {
      $this->rows++;
      $this->fields = explode(';', $this->data);
      $this->elements += count($this->fields);

      // Call functions.
      $this->callFunctions();
    }
  }

  // Open and close file.
  public function doHandle()
  {
    if (!$this->handleIsOpen) {
      $this->handle = fopen($this->file, "r");
      $this->data = fgetcsv($this->handle, 1000)[0];
      $this->handleIsOpen = true;
      return true;
    }
    $this->data = fgetcsv($this->handle, 1000)[0];
    if ($this->data) {
      return true;
    }
    fclose($this->handle);
    $this->handleIsOpen = false;
    return false;
  }

  // Call all function delclareted in functionToCallArray.
  public function callFunctions(): void
  {
    $counter = count($this->functionToCallArray);
    while ($counter--) {
      // Do all checks.
      $functionToCall = $this->functionToCallArray[$counter];
      $this->report->reportRecord($functionToCall, $this->$functionToCall());
    }
  }

  // Check file type (text/plain).
  public function checkFileType(): bool
  {
    if (mime_content_type($this->file) == "text/plain") {
      return true;
    }
    $this->messageType[] = "error";
    $this->message[] = "Your file type is not correct.";
    return false;
  }

  // Check if file is emtpy.
  public function checkFileEmpty(): bool
  {
    if (preg_match('/[a-z]{3}/', $this->data)) {
      return true;
    }
    $this->messageType[] = "error";
    $this->message[] = "Your file is empty.";
    return false;
  }

  // Check if csv key name exist.
  public function checkKeyValuesName(): bool
  {
    foreach ($this->fields as $field) {
      if (!in_array(strtolower($field), $this->csvKeyValuesName)) {
        $this->messageType[] = "error";
        $this->message[] = "Some information (key value) in your file is missing.";
        return false;
      }
    }
    return true;
  }

  // Check all values needed exist.
  public function checkKeyValuesValue(): bool
  {
    $asValues = [];
    $results = [];
    foreach ($this->fields as $field) {
      foreach ($this->csvKeyValues as $value) {
        if ($field == $value->getName()) {
          $asValues[] = $value->getasValue();
        }
      }
    }
    $results = array_diff($this->csvKeyValuesValue, $asValues);
    if ($results) {
      $value = "";
      foreach ($results as $result) {
        $value .= $result . ' ';
      }
      $this->messageType[] = "error";
      $this->message[] = $value . ": information/s (key value) in your file is missing.";
      return false;
    }
    return true;
  }

  // Set key values in all variables.
  public function setKeyValues(): bool
  {
    $name = [];
    $types = [];
    foreach ($this->fields as $key => $field) {
      if (in_array(strtolower($field), $this->csvKeyValuesName)) {
        $tmp = $this->csvKeyValuesRepository->findOneByName($field);
        $this->csvKeyValuePositions[strtolower($tmp->getAsValue())] = $key;
        $name[$key] = $tmp->getName();
        $types[$key] = $tmp->getType();
      }
    }
    $this->csvKeyValuesName = $name;
    $this->csvKeyValueTypes = $types;
    return true;
  }

  // Check if number of fields is ok ex: row*line=fields.
  public function checkFieldQuantity(): bool
  {
    if ($this->rows * $this->columns > $this->elements) {
      $this->messageType[] = "error";
      $this->message[] = "Rows end coloumns do not correspont with Head row. Too much fields filled.";
      return false;
    }
    if ($this->rows * $this->columns < $this->elements) {
      $this->messageType[] = "error";
      $this->message[] = "Some information (body) in your file is missing.";
      return false;
    }
    return true;
  }

  // Check if field is empty.
  public function checkEmptyField(): bool
  {
    $this->emptyField = [];
    foreach ($this->fields as $key => $field) {
      if (!preg_match('/[a-z0-9]{2}/', $field)) {
        $this->emptyField[] = "{$this->csvKeyValuesName[$key]} in Row {$this->rows} is empty.";
      }
    }
    if ($this->emptyField) {
      foreach ($this->emptyField as $empty) {
        $this->messageType[] = "error";
        $this->message[] = $empty;
      }
      return false;
    }
    return true;
  }

  // Check file type (alphabetic, numeric, email, alphanumeric).
  public function checkCsvKeyValueTypes(): bool
  {
    foreach ($this->fields as $key => $field) {

      switch ($this->csvKeyValueTypes[$key]) {
        case 'alpha':
          if (!ctype_alpha($field)) {
            $this->messageType[] = "error";
            $this->message[] = "{$this->csvKeyValuesName[$key]} in Row {$this->rows} is not alphabetic.";;
            return false;
          }
          break;
        case 'num':
          if (!ctype_digit($field)) {
            $this->messageType[] = "error";
            $this->message[] = "{$this->csvKeyValuesName[$key]} in Row {$this->rows} is not numeric.";;
            return false;
          }
          break;
        case 'alphanum':
          if (!ctype_alnum($field)) {
            $this->messageType[] = "error";
            $this->message[] = "{$this->csvKeyValuesName[$key]} in Row {$this->rows} is not alphanumeric.";;
            return false;
          }
          break;

        case 'email':
          if (!filter_var($field, FILTER_VALIDATE_EMAIL)) {
            $this->messageType[] = "error";
            $this->message[] = "{$this->csvKeyValuesName[$key]} in Row {$this->rows} is not a email address.";;
            return false;
          }
          break;

        default:
          $this->messageType[] = "error";
          $this->message[] = "{$this->csvKeyValuesName[$key]} in Row {$this->rows} has no type.";;
          return false;
          break;
      }
    }
    return true;
  }

  // Check is email already exist.
  public function checkEmailExist(): bool
  {
    if ($this->userRepository->findOneByEmail($this->fields[$this->csvKeyValuePositions['email']])) {
      $this->messageType[] = "error";
      $this->message[] = "{$this->csvKeyValuesName[$this->csvKeyValuePositions['email']]} in Row {$this->rows} already exist.";
      return false;
    }
    return true;
  }

  // Create a table to show user wich field is wrong.
  public function createErrorTable(): bool
  {
    $tmpRow = 0;
    $errors = $this->report->getSessionFlashbag()->get('error', []);
    $errorCount = count($errors);
    $tmpErrorCount = 0;
    if ($tmpErrorCount != $errorCount) {
      $tmpErrorCount = $errorCount;
      if ($tmpRow != $this->rows) {
        $tmpRow = $this->rows;
        $this->errorTable['fields'][] = $this->fields;
        $this->errorTable['errors'][] = $errors;
      }
    }
    return true;
  }

  // After check if line is ok save user in database.
  public function saveUser(): bool
  {
    if (!in_array($this->fields, $this->errorTable['fields'])) {
      $company = $this->user->getCompany();
      $newUser = new User();
      $password = $this->userPasswordEncoderInterface->encodePassword($newUser, uniqid());
      $newUser->setFirstname($this->fields[$this->csvKeyValuePositions['firstname']])
        ->setLastname($this->fields[$this->csvKeyValuePositions['lastname']])
        ->setEmail($this->fields[$this->csvKeyValuePositions['email']])
        ->setCountry("non")
        ->setCity("non")
        ->setLanguage($company->getLanguage())
        ->setPassword($password)
        ->setRoles(["ROLE_USER"])
        ->setLevelUser(0)
        ->setIsNew(1)
        ->setCompany($company)
        ->setAdmin(false);
      $this->entityManagerInterface->persist($newUser);
      $this->entityManagerInterface->flush();
      $this->messageType[] = "success";
      $this->message[] = $newUser->getFirstname() . ' ' . $newUser->getLastname() . 'is registred';
      return true;
    }
    return false;
  }

  public function getFile(): string
  {
    return $this->file;
  }

  public function setFile($file)
  {
    $this->file = $file;
  }

  public function getErrorTable(): ?array
  {
    if ($this->errorTable) {
      array_unshift($this->errorTable, $this->csvKeyValuesName);
      return $this->errorTable;
    }
    return null;
  }
}
