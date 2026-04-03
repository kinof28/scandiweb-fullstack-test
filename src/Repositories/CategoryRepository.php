<?php

namespace App\Repositories;

use App\Models\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CategoryRepository
{
  private EntityRepository $repository;

  public function __construct(private readonly EntityManagerInterface $em)
  {
    $this->repository = $em->getRepository(Category::class);
  }

  public function findByName(string $name): ?Category
  {
    return $this->repository->find($name);
  }

  /** @return Category[] */
  public function findAll(): array
  {
    return $this->repository->findAll();
  }

  public function save(Category $category, bool $flush = true): void
  {
    $this->em->persist($category);
    if ($flush) {
      $this->em->flush();
    }
  }


  public function delete(Category $category, bool $flush = true): void
  {
    $this->em->remove($category);
    if ($flush) {
      $this->em->flush();
    }
  }
}