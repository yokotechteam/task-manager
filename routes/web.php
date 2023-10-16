<?php
require_once "../vendor/autoload.php";

use App\Controllers\User;
use App\Controllers\Page;

$routes = [ 
  '/home'         => [ User::class, 'index' ],
  '/login'        => [ User::class, 'login' ],
  '/register'     => [ User::class, 'register' ],
  '/email-verify' => [ User::class, 'email_verify' ],
];

// Local development
// $ROOT  = '/task-manager/public';
$route = str_replace ( $ROOT, '', $_SERVER[ 'REQUEST_URI' ] );

//Deploy 
$route = $_SERVER[ 'REQUEST_URI' ];

if ( $route === '/' )
{
  // Deploy
  // return header ( "Location: home" );
  return header ( "Location: home" );
}
if ( ! array_key_exists ( $route, $routes ) )
{
  $ctrl = new Page;
  return $ctrl->page_not_found ();
}

session_start ();

$controller = $routes[ $route ][ 0 ];
$method     = $routes[ $route ][ 1 ];
$ctrl       = new $controller ();
$ctrl->$method ();