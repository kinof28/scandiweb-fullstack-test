<?php
namespace App\Models;
class TechProduct extends Product
{
  public function __construct()
  {
    $this->category = new Category('tech');
  }

}