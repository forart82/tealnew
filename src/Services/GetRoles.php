<?php

namespace App\Services;

use App\Entity\User;

/**
 * Contains a class who return a list of roles linked to the user
 */

class GetRoles
{
  private $roles;
  public function __construct(){
    $this->roles=[];
  }
  public function getRoles(string $role, array $roles){

    if(array_key_exists($role, $roles))
    {
      foreach($roles[$role] as $element)
      {
        $this->roles[]=$element;
        $this->getRoles($element,$roles);
      }
    }
    array_unshift($this->roles,$role);
    return array_unique($this->roles);
  }
}
