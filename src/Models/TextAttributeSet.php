<?php

namespace App\Models;

class TextAttributeSet extends AttributeSet
{
  public function __construct()
  {
    $this->type = "text";
  }
}