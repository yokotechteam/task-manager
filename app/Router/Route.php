<?php
namespace App\Router;


class Route
{
  public static function handle ( $method = 'GET', $path = '/', $filename = '' )
  {
    $current_method = $_SERVER[ 'REQUEST_METHOD' ];
    $current_uri    = $_SERVER[ 'REQUEST_URI' ];
    if ( $current_method !== $method )
    {
      return false;
    }
    // Local development
    // define ( 'ROOT_PATH', '/task-manager/public' );
    // $pattern = '#^' . ROOT_PATH . $path . '$#siD';

    // deploy
    $pattern = '#^' . $path . '$#siD';


    if ( preg_match ( $pattern, $current_uri ) )
    {
      if ( is_callable ( $filename ) )
      {
        $file = $filename ();
        if ( $file )
        {
          return $file;
        }
        return false;
      }
      else
      {
        $base_dir = __DIR__ . "/";
        $filename = $base_dir . "../../resources/views/" . $filename . '.php';
        return require_once $filename;
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