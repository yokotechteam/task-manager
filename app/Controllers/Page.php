<?php
namespace App\Controllers;

use App\Router\Route;

class Page
{
  public function email_verify ()
  {
    Route::get ( '/email-verify', function ()
    {
      session_start ();
      var_dump ( $_SESSION[ 'opt' ] );
      return view ( 'email_verify' );
    } );
  }
}