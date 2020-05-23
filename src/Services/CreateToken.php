<?php

namespace App\Services;

use App\Controller\UserController;

const TOKENVALUES = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

class CreateToken
{

  public static function create(): string
  {
    $token="";
    $rand=mt_rand(30,90);
    for ($i = 0; $i < $rand; $i++) {
      $token.= TOKENVALUES[mt_rand(0, 61)];
    }
    return $token;  	
  }
}
