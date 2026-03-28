<?php

namespace App\Models;
class Currency
{
  public function __construct(string $label, string $symbol)
  {
    $this->label = $label;
    $this->symbol = $symbol;
  }
  public string $label;
  public string $symbol;

}