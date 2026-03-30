<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\Types\PaginatedProductsType;
use App\GraphQL\Types\ProductType;
use App\Services\ProductService;
use GraphQL\Type\Definition\Type;

class ProductQuery
{
  public function __construct(
    private readonly ProductService $productService,
    private readonly ProductType $productType,
    private readonly PaginatedProductsType $paginatedProductsType,
  ) {
  }

  /**
   * product(id: Int!): Product
   *
   * @return array<string, mixed>
   */
  public function getOne(): array
  {
    return [
      'type' => $this->productType,
      'description' => 'Fetch a single product by ID.',
      'args' => [
        'id' => ['type' => Type::nonNull(Type::int())],
      ],
      'resolve' => fn(mixed $root, array $args) =>
        $this->productService->getProductById($args['id']),
    ];
  }

  /**
   * products(limit: Int, offset: Int, categoryId: Int): PaginatedProducts!
   *
   * @return array<string, mixed>
   */
  public function getAll(): array
  {
    return [
      'type' => Type::nonNull($this->paginatedProductsType),
      'description' => 'Fetch a paginated list of products, optionally filtered by category.',
      'args' => [
        'limit' => [
          'type' => Type::int(),
          'defaultValue' => 20,
        ],
        'offset' => [
          'type' => Type::int(),
          'defaultValue' => 0,
        ],
        'categoryId' => [
          'type' => Type::int(),
          'description' => 'Filter products by category ID.',
        ],
      ],
      'resolve' => fn(mixed $root, array $args) =>
        $this->productService->getPaginatedProducts(
          $args['limit'],
          $args['offset'],
          $args['categoryId'] ?? null,
        ),
    ];
  }
}