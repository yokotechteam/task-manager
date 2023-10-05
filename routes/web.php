<?php
require_once "../vendor/autoload.php";

use App\Controllers\User;
use App\Controllers\Page;

$ROOT = '/task-manager/public';

$routes = [ 
  '/'             => [ User::class, 'index' ],
  '/login'        => [ User::class, 'login' ],
  '/register'     => [ User::class, 'register' ],
  '/email-verify' => [ Page::class, 'email_verify' ]
];
$route  = str_replace ( $ROOT, '', $_SERVER[ 'REQUEST_URI' ] );

if ( ! array_key_exists ( $route, $routes ) )
{
  return false;
}

$controller = $routes[ $route ][ 0 ];
$method     = $routes[ $route ][ 1 ];

$ctrl = new $controller ();
$ctrl->$method ();