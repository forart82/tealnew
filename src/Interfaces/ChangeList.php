<?php

/**
 * @file
 * Conatins a interface for changelistjavascript function.
 */

namespace App\Interfaces;

interface ChangeList
{
  /**
   * Is use for changeList function in a javscript file
   * Returns an JsonResponse.
   * @return Response
   */
  public function changeList();
}
