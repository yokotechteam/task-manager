<?php
namespace App\Controllers;

use PDO;

class Database
{
  protected $pdo;
  public function __construct ()
  {
    [ $db_connection, $db_host, $db_name, $db_username, $db_password ] = db_config ();
    // $this->pdo                                                         = new PDO ( "$db_connection:dbname=$db_name;host=$db_host;", $db_username, $db_password, [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ] );
  }

}
