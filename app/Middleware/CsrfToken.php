<?php
namespace App\Middleware;

// session_start ();
class CsrfToken
{

  public static function generate ( $hour = 3600 )
  {
    if ( ! self::check () )
    {
      $_SESSION[ '_token_exp' ] = time () + $hour;
      if ( function_exists ( 'random_bytes' ) )
      {
        $_SESSION[ '_token' ] = bin2hex ( random_bytes ( 32 ) );
      }
      else if ( function_exists ( 'mcrypt_create_iv' ) )
      {
        $_SESSION[ '_token' ] = bin2hex ( mcrypt_create_iv ( 32, MCRYPT_DEV_URANDOM ) );
      }
      else
      {
        $_SESSION[ '_token' ] = bin2hex ( openssl_random_pseudo_bytes ( 32 ) );
      }
    }
  }
  public static function check ()
  {
    $status = true;
    if ( ! empty( $_SESSION[ '_token' ] ) && ! empty( $_SESSION[ '_token_exp' ] ) )
    {
      if ( $_SERVER[ 'REQUEST_METHOD' ] === "POST" )
      {
        // POST
        if (
          ! hash_equals ( $_SESSION[ '_token' ], $_POST[ '_token' ] ) ||
          $_SESSION[ '_token_exp' ] < time ()
        )
        {
          session_destroy ();
          $status = false;
          return $status;
        }
        else
        {
          session_destroy ();
          return $status;
        }
      }
      else
      {
        // GET
        if ( $_SESSION[ '_token_exp' ] < time () )
        {
          session_destroy ();
          $status = false;
          return $status;
        }
        else
        {
          return $status;
        }
      }

    }
    else
    {
      $status = false;
      return $status;
    }
  }
}