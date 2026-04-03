<?php

namespace App\Exceptions;

use RuntimeException;

class ValidationException extends RuntimeException
{
  public function __construct(string $message, int $code = 422)
  {
    parent::__construct($message, $code);
  }
}