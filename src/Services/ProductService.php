<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AllProduct;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\ClothesProduct;
use App\Models\Price;
use App\Models\Product;
use App\Models\SwatchAttributeSet;
use App\Models\TechProduct;
use App\Models\TextAttributeSet;
use App\Repositories\CurrencyRepository;
use App\Repositories\ProductRepository;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;

class ProductService
{
  public function __construct(
    private readonly ProductRepository $productRepository,
    private readonly CurrencyRepository $currencyRepository,
  ) {
  }

  // -------------------------------------------------------------------------
  // Queries
  // -------------------------------------------------------------------------

  public function getProductById(string $id): Product
  {
    $product = $this->productRepository->findById($id);
    if ($product === null) {
      throw new NotFoundException("Product '{$id}' not found.");
    }
    return $product;
  }

  /** @return Product[] */
  public function getAllProducts(): array
  {
    return $this->productRepository->findAll();
  }

  /** @return Product[] */
  public function getProductsByCategory(string $category): array
  {
    return $this->productRepository->findByCategory($category);
  }

  // -------------------------------------------------------------------------
  // Mutations
  // -------------------------------------------------------------------------

  /**
   * @param array{
   *   id: string,
   *   name: string,
   *   type: string,
   *   category: string,
   *   brand?: string,
   *   description?: string,
   *   inStock?: bool,
   *   gallery?: string[],
   *   prices?: array<array{amount: float, currencyLabel: string}>,
   *   attributes?: array<array{
   *     id: string, name: string, type: string,
   *     items: array<array{id: string, value: string, displayValue: string}>
   *   }>
   * } $data
   */
  public function createProduct(array $data): Product
  {
    $this->validateProductData($data);

    if ($this->productRepository->findById($data['id']) !== null) {
      throw new ValidationException("Product with id '{$data['id']}' already exists.");
    }

    $product = $this->makeProductInstance($data['type']);

    $product->setId($data['id'])
      ->setName($data['name'])
      ->setCategory($data['category'])
      ->setBrand($data['brand'] ?? null)
      ->setDescription($data['description'] ?? null)
      ->setInStock($data['inStock'] ?? true)
      ->setGallery($data['gallery'] ?? []);

    foreach ($data['prices'] ?? [] as $priceData) {
      $currency = $this->currencyRepository->findByLabel($priceData['currencyLabel']);
      if ($currency === null) {
        throw new ValidationException("Currency '{$priceData['currencyLabel']}' not found.");
      }

      $price = new Price();
      $price->setAmount($priceData['amount'])
        ->setCurrency($currency);

      $product->addPrice($price);
    }

    foreach ($data['attributes'] ?? [] as $setData) {
      $attributeSet = $setData['type'] === 'swatch'
        ? new SwatchAttributeSet()
        : new TextAttributeSet();

      $attributeSet->setId($setData['id'])
        ->setName($setData['name']);

      foreach ($setData['items'] as $itemData) {
        $attribute = new Attribute();
        $attribute->setId($itemData['id'])
          ->setValue($itemData['value'])
          ->setDisplayValue($itemData['displayValue']);

        $attributeSet->addItem($attribute);
      }

      $product->addAttributeSet($attributeSet);
    }

    $this->productRepository->save($product);

    return $product;
  }

  public function deleteProduct(string $id): void
  {
    $product = $this->getProductById($id);
    $this->productRepository->delete($product);
  }

  private function makeProductInstance(string $type): Product
  {
    return match ($type) {
      'tech' => new TechProduct(),
      'clothes' => new ClothesProduct(),
      'all' => new AllProduct(),
      default => throw new ValidationException(
        "Invalid product type '{$type}'. Allowed: tech, clothes, all."
      ),
    };
  }

  private function validateProductData(array $data): void
  {
    $errors = [];

    if (empty($data['id'])) {
      $errors[] = 'Product id is required.';
    }
    if (empty($data['name'])) {
      $errors[] = 'Product name is required.';
    }
    if (empty($data['type'])) {
      $errors[] = 'Product type is required.';
    }
    if (empty($data['category'])) {
      $errors[] = 'Product category is required.';
    }

    if (!empty($errors)) {
      throw new ValidationException(implode(' ', $errors));
    }
  }
}