<?php

namespace App\Services;

const TOKENVALUES = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

class CreateToken
{
  public static function create(): string
  {
    $token="";
    for ($i = 0; $i < 30; $i++) {
      $token.= TOKENVALUES[mt_rand(0, 61)];
    }
    return $token;
  }
}
