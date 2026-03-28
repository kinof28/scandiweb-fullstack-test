<?php
namespace App\Models;
class AllProduct extends Product
{
  public function __construct()
  {
    $this->category = new Category('all');
  }

}