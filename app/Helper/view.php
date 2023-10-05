<?php

function view ( $filename = '' )
{
  $base_dir = __DIR__ . "/";
  $filename = $base_dir . "../../resources/views/" . $filename . '.php';
  if ( file_exists ( $filename ) )
  {
    return require_once $filename;
  }
  return false;
}