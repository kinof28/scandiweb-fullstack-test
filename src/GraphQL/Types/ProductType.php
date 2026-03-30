<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Product;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductType extends ObjectType
{
  public function __construct(CategoryType $categoryType)
  {
    parent::__construct([
      'name' => 'Product',
      'description' => 'A product available for purchase.',
      'fields' => [
        'id' => [
          'type' => Type::nonNull(Type::int()),
          'resolve' => fn(Product $product) => $product->getId(),
        ],
        'name' => [
          'type' => Type::nonNull(Type::string()),
          'resolve' => fn(Product $product) => $product->getName(),
        ],
        'description' => [
          'type' => Type::string(),
          'resolve' => fn(Product $product) => $product->getDescription(),
        ],
        'price' => [
          'type' => Type::nonNull(Type::float()),
          'description' => 'Price in the default currency.',
          'resolve' => fn(Product $product) => $product->getPrice(),
        ],
        'stock' => [
          'type' => Type::nonNull(Type::int()),
          'description' => 'Available units in stock.',
          'resolve' => fn(Product $product) => $product->getStock(),
        ],
        'category' => [
          'type' => $categoryType,
          'description' => 'The category this product belongs to.',
          'resolve' => fn(Product $product) => $product->getCategory(),
        ],
        'createdAt' => [
          'type' => Type::nonNull(Type::string()),
          'resolve' => fn(Product $product) => $product->getCreatedAt()->format(DATE_ATOM),
        ],
        'updatedAt' => [
          'type' => Type::nonNull(Type::string()),
          'resolve' => fn(Product $product) => $product->getUpdatedAt()->format(DATE_ATOM),
        ],
      ],
    ]);
  }
}