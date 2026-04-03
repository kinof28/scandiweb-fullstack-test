<?php

namespace App\Services;

use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;

class CurrencyService
{
  public function __construct(private readonly CurrencyRepository $currencyRepository)
  {
  }

  public function getCurrencyByLabel(string $label): Currency
  {
    $currency = $this->currencyRepository->findByLabel($label);
    if ($currency === null) {
      throw new NotFoundException("Currency '{$label}' not found.");
    }
    return $currency;
  }

  /** @return Currency[] */
  public function getAllCurrencies(): array
  {
    return $this->currencyRepository->findAll();
  }

  public function createCurrency(string $label, string $symbol): Currency
  {
    if (empty(trim($label))) {
      throw new ValidationException('Currency label cannot be empty.');
    }

    if ($this->currencyRepository->findByLabel($label) !== null) {
      throw new ValidationException("Currency '{$label}' already exists.");
    }

    $currency = new Currency();
    $currency->setLabel($label)
      ->setSymbol($symbol);

    $this->currencyRepository->save($currency);

    return $currency;
  }
}