<?php

namespace App\Repositories;

use App\Models\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProductRepository
{
  private EntityRepository $repository;

  public function __construct(private readonly EntityManagerInterface $em)
  {
    $this->repository = $em->getRepository(Product::class);
  }

  public function findById(string $id): ?Product
  {
    return $this->repository->find($id);
  }

  /** @return Product[] */
  public function findAll(): array
  {
    return $this->repository->findAll();
  }

  /** @return Product[] */
  public function findByCategory(string $category): array
  {
    return $this->repository->findBy(['category' => $category]);
  }

  public function save(Product $product, bool $flush = true): void
  {
    $this->em->persist($product);
    if ($flush) {
      $this->em->flush();
    }
  }

  public function delete(Product $product, bool $flush = true): void
  {
    $this->em->remove($product);
    if ($flush) {
      $this->em->flush();
    }
  }
}