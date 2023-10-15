<?php
namespace App\Controllers;


class Page
{
  public function page_not_found ()
  {
    return view ( 'page_not_found' );
  }
  public function method_not_allow ()
  {
    return view ( 'method_not_allow' );
  }

}