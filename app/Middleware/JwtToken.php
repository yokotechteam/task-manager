<?php
declare(strict_types=1);
namespace App\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait JwtToken
{

  public function encode ()
  {
    $domainName = $this->domainName;
    $secret_Key = $this->secret_Key;

    $date      = $this->date;
    $expire_at = $date->modify ( $this->expire_at )->getTimestamp (); // Add 60 seconds

    $username   = $this->username; // Retrieved from filtered POST data
    $user_email = $this->user_email;

    $isLogin = $this->isLogin;

    $payload = [ 
      'iat'       => $date->getTimestamp (),
      'iss'       => $domainName,
      'sub'       => $domainName,
      "aud"       => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
      'nbf'       => $date->getTimestamp (),
      'exp'       => $expire_at,
      'userName'  => $username,
      'userEmail' => $user_email,
      'isLogin'   => $isLogin,
    ];
    try
    {
      $encoded_data = JWT::encode (
        $payload,
        $secret_Key,
        'HS512',
      );
      return $encoded_data;
    }
    catch ( Exception $e )
    {
      echo $e->getMessage ();
      return false;
    }
  }
  // abstract public function encode ();
  public function decode ( $jwtToken )
  {
    try
    {
      return JWT::decode ( $jwtToken, new Key ( $this->secret_Key, 'HS512' ) );
    }
    catch ( Exception $e )
    {
      $e->getMessage ();
      return false;
    }
  }

}