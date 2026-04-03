<?php

namespace App\Repositories;

use App\Models\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CurrencyRepository
{
  private EntityRepository $repository;

  public function __construct(private readonly EntityManagerInterface $em)
  {
    $this->repository = $em->getRepository(Currency::class);
  }

  public function findByLabel(string $label): ?Currency
  {
    return $this->repository->find($label);
  }

  /** @return Currency[] */
  public function findAll(): array
  {
    return $this->repository->findAll();
  }

  public function save(Currency $currency, bool $flush = true): void
  {
    $this->em->persist($currency);
    if ($flush) {
      $this->em->flush();
    }
  }
}