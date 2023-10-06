<?php
namespace App\Controllers;

use PDO;
use App\Router\Route;
use App\Models\User as User_Model;

class User
{
  protected $user_model;
  public function __construct ()
  {
    $this->user_model = new User_Model;
  }
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
          $code      = $this->random_string ( 10 );
          $subject   = 'Task Manager Verification Code';
          $body      = "
            <h4>Task Manager Verification Code</h4>
            <br>
            Hi $name,
            <br>
            We received a request to access your Account $email through your email address. Your Task verification code is:
            <br>
              <h1>$code</h1>
            <br>
            This link will expire in 30 minutes.
            <br>
            Best,
              <br>
            The YoKo Team";
          $is_mailed = mailer (
            true,
            [ 'address' => $email, 'name' => $name ],
            [ 'subject' => $subject, 'body' => $body ]
          );
          if ( $is_mailed )
          {
            $code      = $this->random_string ( 5 ) . $code . $this->random_string ( 5 );
            $hash_code = password_hash ( $code, PASSWORD_DEFAULT );
            setcookie ( "_opt", $hash_code, time () + 3600 );


            $this->user_model->insert_into ( [ 
              'name'              => $name,
              'email'             => $email,
              'password'          => $password,
              'hash_password'     => password_hash ( $password, PASSWORD_DEFAULT ),
              'verification_code' => $hash_code
            ] );

            return header ( "Location: email-verify" );
          }
          setcookie ( 'mail_send_error', "We have some error while sending to your email" );
        }

      }

      header ( "Location: register" );


    } );
    Route::get ( '/register', function ()
    {
      return view ( 'register' );
    } );
  }
  public function email_verify ()
  {
    Route::post ( '/email-verify', function ()
    {
      $opt = $_POST[ 'opt' ];
      $this->user_model->select ( [ 'email_verified', 'verification_code' ], [ 'email' => '' ] );

    } );
    Route::get ( '/email-verify', function ()
    {
      $this->user_model->select ( [ 'email_verified' ], [ 'email' => '' ] );
      if ( ! isset( $_COOKIE[ '_opt' ] ) )
      {
        setcookie ( "email_verification_code_time_out", "Your validation code is invalid", time () + 3600 );
        setcookie ( "email_verification_code_time_out", "", time () - 3600 );
        return header ( 'Location: register' );
        // exit();
      }
      return view ( 'email_verify' );
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
    $email = $this->user_model->select ( [ 'email' ], condition: [ "email" => $email ] );

    if ( $email )
    {
      setcookie ( "email_taken", "Email is already Taken", time () + 3600 );
      return $email;
    }
    return false;
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