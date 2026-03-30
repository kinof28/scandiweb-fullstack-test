<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\GraphQL\Types\ProductType;
use App\Services\ProductService;
use GraphQL\Type\Definition\Type;

class ProductMutation
{
  public function __construct(
    private readonly ProductService $productService,
    private readonly ProductType $productType,
  ) {
  }

  /**
   * createProduct(name: String!, price: Float!, stock: Int!, categoryId: Int, description: String): Product!
   *
   * @return array<string, mixed>
   */
  public function create(): array
  {
    return [
      'type' => Type::nonNull($this->productType),
      'description' => 'Create a new product.',
      'args' => [
        'name' => ['type' => Type::nonNull(Type::string())],
        'description' => ['type' => Type::string()],
        'price' => ['type' => Type::nonNull(Type::float())],
        'stock' => ['type' => Type::nonNull(Type::int())],
        'categoryId' => [
          'type' => Type::int(),
          'description' => 'ID of the category to assign.',
        ],
      ],
      'resolve' => fn(mixed $root, array $args) =>
        $this->productService->createProduct($args),
    ];
  }

  /**
   * updateProduct(id: Int!, name: String, description: String, price: Float, stock: Int, categoryId: Int): Product!
   *
   * @return array<string, mixed>
   */
  public function update(): array
  {
    return [
      'type' => Type::nonNull($this->productType),
      'description' => 'Update an existing product. Only provided fields are updated.',
      'args' => [
        'id' => ['type' => Type::nonNull(Type::int())],
        'name' => ['type' => Type::string()],
        'description' => ['type' => Type::string()],
        'price' => ['type' => Type::float()],
        'stock' => ['type' => Type::int()],
        'categoryId' => ['type' => Type::int()],
      ],
      'resolve' => function (mixed $root, array $args) {
        $id = $args['id'];
        $data = array_filter($args, fn($key) => $key !== 'id', ARRAY_FILTER_USE_KEY);
        return $this->productService->updateProduct($id, $data);
      },
    ];
  }

  /**
   * deleteProduct(id: Int!): Boolean!
   *
   * @return array<string, mixed>
   */
  public function delete(): array
  {
    return [
      'type' => Type::nonNull(Type::boolean()),
      'description' => 'Delete a product by ID.',
      'args' => [
        'id' => ['type' => Type::nonNull(Type::int())],
      ],
      'resolve' => function (mixed $root, array $args): bool {
        $this->productService->deleteProduct($args['id']);
        return true;
      },
    ];
  }
}