<?php
namespace App\Controllers;

use PDO;
use App\Router\Route;
use App\Controllers\Database;

class User extends Database
{
  public function index ()
  {
    Route::get ( '/', function ()
    {
      return view ( 'welcome' );
    } );
  }
  public function login ()
  {
    if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' )
    {
      // something
    }
    Route::get ( '/login', function ()
    {
      return view ( 'login' );
    } );
  }
  public function register ()
  {
    Route::post ( '/register', function ()
    {
      session_start ();
      $name             = $_POST[ 'name' ];
      $email            = $_POST[ 'email' ];
      $password         = $_POST[ 'password' ];
      $confirm_password = $_POST[ 'confirm_password' ];
      $status           = $this->validate ( [ "name" => $name, "email" => $email, "password" => $password, "confirm_password" => $confirm_password ] );
      if ( $status )
      {
        $is_taken = $this->email_exists ( $email );
        if ( ! $is_taken )
        {
          $code              = $this->random_string ( 10 );
          $_SESSION[ 'opt' ] = $this->random_string ( 5 ) . $code . $this->random_string ( 5 );

          $sql      = "INSERT INTO users (name, email, password, hash_password, verification_code) VALUES (:name, :email, :password, :hash_password, :verification_code)";
          $pdo_stmt = $this->pdo->prepare ( $sql );
          $result   = $pdo_stmt->execute ( [ 
            ":name"              => $name,
            ":email"             => $email,
            ":password"          => $password,
            ":hash_password"     => password_hash ( $password, PASSWORD_DEFAULT ),
            ":verification_code" => password_hash ( $code, PASSWORD_DEFAULT )
          ] );
          header ( "Location: email-verify" );
          exit();
        }

      }
      header ( "Location: register" );


    } );
    Route::get ( '/register', function ()
    {
      return view ( 'register' );
    } );
  }

  public function validate ( $type )
  {
    if ( ! strlen ( $type[ 'name' ] ) && ! strlen ( $type[ 'email' ] ) && ! strlen ( $type[ 'password' ] ) && ! strlen ( $type[ 'confirm_password' ] ) )
    {
      setcookie ( "form_empty", "You Need To Fill The Form", time () + 3600 );
      return false;
    }

    $name = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{8,100}$/";
    if ( ! preg_match ( $name, $type[ 'name' ] ) )
    {
      setcookie ( "name_error", "At Least One Upper,One Lower And One Number In a Range Of 8 to 100", time () + 3600 );
      return false;
    }

    $email = "@gmail.com";
    if ( ! str_ends_with ( $type[ 'email' ], $email ) )
    {
      setcookie ( "email_error", "Invalid Gmail Format", time () + 3600 );
      return false;
    }

    $password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!|&])[A-Za-z\d@$!|&]{8,20}$/";
    if ( ! preg_match ( $password, $type[ 'password' ] ) )
    {
      setcookie ( "password_error", "Minimum eight and maximum 16 characters, at least one uppercase letter, one lowercase letter, one number and one special character", time () + 3600 );
      return false;
    }
    if ( $type[ 'password' ] !== $type[ 'confirm_password' ] )
    {
      setcookie ( "confirm_password_error", 'Password do not match', time () + 3600 );
      return false;
    }
    return true;
  }
  public function email_exists ( $email )
  {
    $sql      = 'SELECT email FROM users WHERE email = :email';
    $pdo_stmt = $this->pdo->prepare ( $sql );
    $status   = $pdo_stmt->execute ( [ 
      ":email" => $email
    ] );
    if ( $status )
    {
      if ( $pdo_stmt->fetch ( PDO::FETCH_ASSOC ) )
      {
        setcookie ( "email_taken", "Email is already Taken", time () + 3600 );
        return true;
      }
      return false;
    }
  }
  public function random_string ( $length )
  {
    $str = random_bytes ( $length );
    $str = base64_encode ( $str );
    $str = str_replace ( [ "+", "/", "=" ], "", $str );
    $str = substr ( $str, 0, $length );
    return $str;
  }
}