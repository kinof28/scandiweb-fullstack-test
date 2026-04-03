<?php

namespace App\Exceptions;

use RuntimeException;

class NotFoundException extends RuntimeException
{
  public function __construct(string $message, int $code = 404)
  {
    parent::__construct($message, $code);
  }
}