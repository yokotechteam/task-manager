<?php

namespace App\Middleware;

use App\Middleware\QuickLoginToken;

class TokenAuth
{

  use QuickLoginToken;
  protected $secret_Key = "QuickLoginToken@@1423";
  public function aa ( $token )
  {
    $this->check ( $token );
  }

}