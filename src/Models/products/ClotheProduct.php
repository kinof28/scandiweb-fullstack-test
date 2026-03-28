<?php
namespace App\Models;
class ClotheProduct extends Product
{
  public function __construct()
  {
    $this->category = new Category('clothes');
  }

}