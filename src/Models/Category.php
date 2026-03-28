<?php
namespace App\Models;
class Category
{
  public function __construct(string $name)
  {
    $this->name = $name;
  }
  private string $name;
  public function getName(): string
  {
    return $this->name;
  }
}