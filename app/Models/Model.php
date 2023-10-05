<?php

namespace App\Models;

use App\Controllers\Database;

class Model extends Database
{
  protected $table;

  public function select ( $cols = '*', $table = null, $condition = [] )
  {
    // cols = [name, email, password] => name, email, password,
    if ( is_array ( $cols ) )
    {
      $temp = "";
      foreach ( $cols as $col )
      {
        $temp = $temp . $col . ", ";
      }
      $temp[ strlen ( $temp ) - 2 ] = "";
      return $temp;
    }

    // if ( ! $condition )
    // {
    //   $SQL      = "SELECT " . $cols . " FROM " . $table;
    //   $pdo_stmt = $this->pdo->prepare ( $SQL );
    //   $pdo_stmt->execute ();
    // }
    // else
    // {
    //   $SQL      = "SELECT " . $cols . " FROM " . $table . " WHERE " . $condition;
    //   $pdo_stmt = $this->pdo->prepare ( $SQL );
    //   $pdo_stmt->execute ();
    // }

  }
  public function insert_into ()
  {

  }

  public function update_set ()
  {

  }

  public function delete ()
  {

  }
}