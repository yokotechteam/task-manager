<?php
namespace App\Middleware;

use App\Middleware\JwtToken;

trait QuickLoginToken
{

  use JwtToken;
  public function generate ()
  {
    $token = $this->encode ();
    return $token;
  }
  public function check ( $cookie )
  {
    $status = null;
    if ( ! empty( $cookie[ '_qLoginToken' ] ) )
    {
      $this->secret_Key = "QuickLoginToken@@1423";
      $token            = $this->decode ( $cookie[ '_qLoginToken' ] );
      if ( $token && $token->isLogin === true && $token->exp > time () )
      {
        return $token;
      }
      setcookie ( "sessionExp", "Your Session is Expired", time () + 3600 );
      setcookie ( "_qLoginToken", "", time () - 3600 );
      $status = false;
      return $status;
    }
    return $status;
  }


}