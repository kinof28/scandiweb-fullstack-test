<?php
namespace App\Models;
class Price
{
  public function __construct(float $amount, Currency $currency)
  {
    $this->amount = $amount;
    $this->currency = $currency;
  }
  public float $amount;
  public Currency $currency;

}