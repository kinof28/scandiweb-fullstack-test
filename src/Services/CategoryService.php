<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;

class CategoryService
{
  public function __construct(private readonly CategoryRepository $categoryRepository)
  {
  }

  public function getCategoryByName(string $name): Category
  {
    $category = $this->categoryRepository->findByName($name);
    if ($category === null) {
      throw new NotFoundException("Category '{$name}' not found.");
    }
    return $category;
  }

  /** @return Category[] */
  public function getAllCategories(): array
  {
    return $this->categoryRepository->findAll();
  }

  public function createCategory(string $name): Category
  {
    if (empty(trim($name))) {
      throw new ValidationException('Category name cannot be empty.');
    }

    if ($this->categoryRepository->findByName($name) !== null) {
      throw new ValidationException("Category '{$name}' already exists.");
    }

    $category = new Category();
    $category->setName($name);

    $this->categoryRepository->save($category);

    return $category;
  }

  public function deleteCategory(string $name): void
  {
    $category = $this->getCategoryByName($name);
    $this->categoryRepository->delete($category);
  }
}