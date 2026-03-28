<?php

namespace App\Models;

class SwatchAttributeSet extends AttributeSet
{
  public function __construct()
  {
    $this->type = "swatch";
  }
}