<?php
namespace App\Models;
class Attribute
{
  public function __construct(string $value, string $displayValue)
  {
    $this->value = $value;
    $this->displayValue = $displayValue;
    $this->id = $displayValue;
  }
  public string $id;
  public string $value;
  public string $displayValue;

}