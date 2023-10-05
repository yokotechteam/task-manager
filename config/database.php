<?php


function db_config ()
{
  $env = parse_ini_file ( '../.env' );


  return [ 
    $env[ 'DB_CONNECTION' ],
    $env[ 'DB_HOST' ],
    $env[ 'DB_NAME' ],
    $env[ 'DB_USERNAME' ],
    $env[ 'DB_PASSWORD' ]
  ];
}