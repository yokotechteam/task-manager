<?php
namespace App\Router;

// local dev
// define ( 'ROOT_PATH', '/task-manager/public' );
class Route
{
  public static function handle ( $method = 'GET', $path = '/', $filename = '' )
  {
    $current_method = $_SERVER[ 'REQUEST_METHOD' ];
    $current_uri    = $_SERVER[ 'REQUEST_URI' ];

    // Local development
    // $pattern = '#^' . ROOT_PATH . $path . '$#siD';

    // deploy
    $pattern = '#^' . $path . '$#siD';


    if ( preg_match ( $pattern, $current_uri ) )
    {
      if ( $current_method !== $method )
      {
        return false;
      }
      if ( is_callable ( $filename ) )
      {
        return $filename ();
      }
      else
      {
        return require_once __DIR__ . "/../../resources/views/" . $filename . ".php";
      }
    }
    return false;
  }
  public static function get ( $path = '/', $filename = '' )
  {
    return self::handle ( 'GET', $path, $filename );
  }
  public static function post ( $path = '/', $filename = '' )
  {
    return self::handle ( 'POST', $path, $filename );
  }
  public static function put ( $path = '/', $filename = '' )
  {
    return self::handle ( 'PUT', $path, $filename );
  }
  public static function patch ( $path = '/', $filename = '' )
  {
    return self::handle ( 'PATCH', $path, $filename );
  }
  public static function delete ( $path = '/', $filename = '' )
  {
    return self::handle ( 'DELETE', $path, $filename );
  }
}