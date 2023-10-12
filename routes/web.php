<?php
require_once "../vendor/autoload.php";

use App\Controllers\User;
// use App\Controllers\Page;

die(var_dump($_SERVER['REQUEST_URI']))
$ROOT = 'http://personal-task-manager-app-6fe4f15227c7.herokuapp.com/';

$routes = [ 
  '/home'         => [ User::class, 'index' ],
  '/login'        => [ User::class, 'login' ],
  '/register'     => [ User::class, 'register' ],
  '/email-verify' => [ User::class, 'email_verify' ]
];
$route  = str_replace ( $ROOT, '', $_SERVER[ 'REQUEST_URI' ] );
// die( var_dump ( $route ) );
if ( $route === '/' )
{
  return header ( "Location: home" );
}
if ( ! array_key_exists ( $route, $routes ) )
{
  return false;
}

session_start ();

$controller = $routes[ $route ][ 0 ];
$method     = $routes[ $route ][ 1 ];


$ctrl = new $controller ();
$ctrl->$method ();
