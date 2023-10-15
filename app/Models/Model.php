<?php

namespace App\Models;

use PDO;
use App\Controllers\Database;

class Model extends Database
{
  protected $table;

  public function select ( $cols = '*', $condition = [] )
  {
    if ( is_array ( $cols ) )
    {
      $temp = "";
      foreach ( $cols as $col )
      {
        $temp = $temp . $col . ", ";
      }
      $cols = substr_replace ( $temp, '', strlen ( $temp ) - 2 );

    }
    if ( ! $condition )
    {
      $SQL      = "SELECT " . $cols . " FROM " . $this->table;
      $pdo_stmt = $this->pdo->prepare ( $SQL );
      $isExe    = $pdo_stmt->execute ();
    }
    else
    {
      $SQL = "SELECT " . $cols . " FROM " . $this->table . " WHERE ";

      $values = [];
      $last   = array_key_last ( $condition );

      if ( $last === 'operator' )
      {
        foreach ( $condition as $key => $value )
        {
          $SQL                  = $SQL . $key . '=' . ':' . $key . $condition[ $last ];
          $values[ ":" . $key ] = $value;
        }
        array_pop ( $values );
        // $SQL = str_replace ( $SQL, '', strlen ( $SQL ) - 20 );
        $SQL = str_replace ( '&operator=:operator&', '', $SQL );
      }
      else
      {
        foreach ( $condition as $key => $value )
        {
          $SQL                  = $SQL . $key . ' = ' . ':' . $key;
          $values[ ':' . $key ] = $value;
        }
      }
      $pdo_stmt = $this->pdo->prepare ( $SQL );
      $isExe    = $pdo_stmt->execute ( $values );
    }
    if ( $isExe )
    {
      return $pdo_stmt->fetchAll ( PDO::FETCH_ASSOC );
    }

  }
  public function insert_into ( $data = [] )
  {
    $SQL    = "INSERT INTO " . $this->table;
    $cols   = "";
    $recs   = "";
    $values = [];
    foreach ( $data as $key => $value )
    {
      $cols = $cols . $key . ", "; // name, email, pass, 
      $recs = $recs . ":" . $key . ", "; // :name, :email, :pass, 

      $values[ ":" . $key ] = $value;
    }
    $cols = substr_replace ( $cols, '', strlen ( $cols ) - 2 );
    $SQL  = $SQL . " (" . $cols . ") ";

    $recs     = substr_replace ( $recs, '', strlen ( $recs ) - 2 );
    $SQL      = $SQL . " VALUES " . " (" . $recs . ") ";
    $pdo_stmt = $this->pdo->prepare ( $SQL );
    $pdo_stmt->execute ( $values );


  }

  public function update_set ( $data = [], $condition = [] )
  {
    $SQL       = "UPDATE " . $this->table . " SET ";
    $data_cols = "";
    $cond_cols = "";
    $values    = [];

    foreach ( $data as $col => $value )
    {
      $data_cols            = $data_cols . $col . " = " . ":" . $col . ", ";
      $values[ ":" . $col ] = $value;
    }
    $data_cols = substr_replace ( $data_cols, '', strlen ( $data_cols ) - 2 );
    $SQL       = $SQL . $data_cols;

    foreach ( $condition as $col => $value )
    {
      $cond_cols            = $cond_cols . $col . " = " . ":" . $col;
      $values[ ":" . $col ] = $value;
    }
    $SQL      = $SQL . " WHERE " . $cond_cols;
    $pdo_stmt = $this->pdo->prepare ( $SQL );
    $pdo_stmt->execute ( $values );

  }

  public function delete_from ( $condition = [] )
  {
    // [id => 1]
    $SQL    = "DELETE FROM " . $this->table . " WHERE ";
    $col    = "";
    $values = [];
    foreach ( $condition as $key => $value )
    {
      # code...
      $col                  = $col . $key . " = :" . $key; // id = :id
      $values[ ":" . $key ] = $value;
    }
    $SQL      = $SQL . $col;
    $pdo_stmt = $this->pdo->prepare ( $SQL );
    $pdo_stmt->execute ( $values );
  }
}