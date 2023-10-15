<?php
namespace App\Controllers;


use DateTimeImmutable;
use App\Router\Route;
use App\Models\User as User_Model;
use App\Middleware\JwtToken;
use App\Middleware\CsrfToken;
use App\Middleware\QuickLoginToken;


class User
{
  use JwtToken;
  use QuickLoginToken;

  protected $user_model;

  protected $domainName = 'firebase-adminsdk-2rkbg@jwt-token-3032f.iam.gserviceaccount.com';
  protected $secret_Key = "EmailVerifyJwtToken@@1423";
  protected $date;
  protected $expire_at = "+10 seconds";
  protected $username;
  protected $user_email;
  protected $isLogin = false;


  public function __construct ()
  {
    $this->user_model = new User_Model;
    // $this->jwtToken   = new JwtToken;
  }
  public function index ()
  {
    Route::get ( '/home', function ()
    {
      $status = $this->check ( $_COOKIE );
      if ( $status === null || $status === false )
      {
        return header ( 'Location: login' );
      }


      if ( ! $this->user_model->select ( [ 'email_verified' ], [ 'email' => $status->userEmail ] )[ 0 ][ 'email_verified' ] )
      {
        $_SESSION[ 'email_not_verified' ] = 'Email Not Verified';
      }

      return view ( 'welcome' );
    } );
    Route::post ( '/home', function ()
    {
      return view ( 'method_not_allow' );
    } );
  }
  public function login ()
  {
    Route::post ( '/login', function ()
    {
      if ( ! CsrfToken::check () )
      {
        setcookie ( '_token_invalid', "Invalid CSRF Token", time () + 3600 );
        return header ( "Location: login" );
      }
      $email    = $_POST[ 'email' ];
      $password = $_POST[ 'password' ];

      $users = $this->user_model->select ( [ 'name', 'email', 'hash_password' ], [ 'email' => $email ] );
      // var_dump ( $users );
      if ( $users )
      {

        $db_pw = $users[ 0 ][ 'hash_password' ];
        if ( password_verify ( $password, $db_pw ) )
        {
          $this->secret_Key = "QuickLoginToken@@1423";
          $this->date       = new DateTimeImmutable ();
          $this->expire_at  = "+7 days"; //
          $this->username   = $users[ 0 ][ 'name' ];
          $this->user_email = $users[ 0 ][ 'email' ];
          $this->isLogin    = true;

          $token = $this->generate ();
          setcookie ( "_qLoginToken", $token, $this->date->modify ( $this->expire_at ) );
          return header ( "Location: home" );
        }
        else
        {
          setcookie ( "invalid_password", "Your Password is not correct", time () + 3600 );
          return header ( "Location: login" );
        }
      }
      else
      {
        setcookie ( "email_not_exists", "Invalid email", time () + 3600 );
        return header ( "Location: login" );
      }

    } );

    Route::get ( '/login', function ()
    {
      CsrfToken::generate ();
      $status = $this->check ( $_COOKIE );

      if ( $status )
      {
        return header ( "Location: home" );
      }
      elseif ( $status === null )
      {
        return view ( 'login' );
      }

      return header ( 'Location: login' );
    } );
  }
  public function register ()
  {
    Route::post ( '/register', function ()
    {
      if ( ! CsrfToken::check () )
      {
        setcookie ( '_csrf_invalid', "Invalid Csrf Token", time () + 3600 );
        return header ( "Location: register" );
      }
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
            [ 'subject' => $subject, 'body' => $body ],
          );
          if ( $is_mailed )
          {
            $this->date      = new DateTimeImmutable ();
            $this->expire_at = "+30 minutes";

            $this->username   = $name;
            $this->user_email = $email;

            $encodedJwtToken = $this->encode ();
            setcookie ( "_jwtToken", $encodedJwtToken, $this->date->modify ( $this->expire_at )->getTimestamp () );

            $code      = $name . $code . $email;
            $hash_code = password_hash ( $code, PASSWORD_DEFAULT );
            $this->user_model->insert_into ( [ 
              'name'              => $name,
              'email'             => $email,
              'password'          => $password,
              'hash_password'     => password_hash ( $password, PASSWORD_DEFAULT ),
              'verification_code' => $hash_code,
            ] );

            return header ( "Location: email-verify" );
          }
          setcookie ( 'mail_send_error', "We have some error while sending to your email" );
        }

      }

      return header ( "Location: register" );


    } );
    Route::get ( '/register', function ()
    {
      CsrfToken::generate ();

      $status = $this->check ( $_COOKIE );

      if ( $status )
      {
        return header ( "Location: home" );
      }
      elseif ( $status === null )
      {
        return view ( 'register' );
      }
      return header ( 'Location: login' );
    } );
  }
  public function email_verify ()
  {
    Route::post ( '/email-verify', function ()
    {
      if ( ! CsrfToken::check () )
      {
        setcookie ( '_csrf_invalid', 'Invalid Csrf Token', time () + 3600 );
        return header ( "Location: email-verify" );
      }
      if ( ! empty( $_COOKIE[ '_jwtToken' ] ) )
      {
        $opt  = $_POST[ 'opt' ];
        $data = $this->decode ( $_COOKIE[ '_jwtToken' ] );
        $iss  = "firebase-adminsdk-2rkbg@jwt-token-3032f.iam.gserviceaccount.com";
        if ( $data && $data->exp > time () && $data->iss === $iss )
        {
          $name     = $data->userName;
          $email    = $data->userEmail;
          $opt      = $name . $opt . $email;
          $info     = $this->user_model->select (
            [ 'verification_code' ],
            [ 'email' => $email ],
          );
          $hash_opt = $info[ 0 ][ 'verification_code' ];
          if ( password_verify ( $opt, $hash_opt ) )
          {
            $this->user_model->update_set ( [ 'email_verified' => 1 ], [ 'email' => $email ] );
            setcookie ( '_jwtToken', "", time () - 3600 );
            setcookie ( "email_verified", "Your email have been verified", time () + 3600 );
            return header ( "Location: login" );
          }
          else
          {
            setcookie ( "optError", "Your verification code was wrong", time () + 3600 );
            return header ( "Location: email-verify" );
          }
        }
        setcookie ( '_jwtToken', "", time () - 3600 );
      }
      setcookie ( "_jwtTokenExpired", "Token Expired!", time () + 3600 );
      return header ( "Location: login" );
    } );
    Route::get ( '/email-verify', function ()
    {
      CsrfToken::generate ();
      if ( empty( $_COOKIE[ '_jwtToken' ] ) )
      {
        return header ( "Location: login" );
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