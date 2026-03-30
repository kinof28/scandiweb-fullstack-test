<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\GraphQL\Types\CategoryType;
use App\Services\CategoryService;
use GraphQL\Type\Definition\Type;

class CategoryMutation
{
  public function __construct(
    private readonly CategoryService $categoryService,
    private readonly CategoryType $categoryType,
  ) {
  }

  /**
   * createCategory(name: String!, description: String): Category!
   *
   * @return array<string, mixed>
   */
  public function create(): array
  {
    return [
      'type' => Type::nonNull($this->categoryType),
      'description' => 'Create a new category.',
      'args' => [
        'name' => [
          'type' => Type::nonNull(Type::string()),
        ],
        'description' => [
          'type' => Type::string(),
        ],
      ],
      'resolve' => fn(mixed $root, array $args) =>
        $this->categoryService->createCategory($args),
    ];
  }

  /**
   * updateCategory(id: Int!, name: String, description: String): Category!
   *
   * @return array<string, mixed>
   */
  public function update(): array
  {
    return [
      'type' => Type::nonNull($this->categoryType),
      'description' => 'Update an existing category.',
      'args' => [
        'id' => ['type' => Type::nonNull(Type::int())],
        'name' => ['type' => Type::string()],
        'description' => ['type' => Type::string()],
      ],
      'resolve' => function (mixed $root, array $args) {
        $id = $args['id'];
        $data = array_filter($args, fn($key) => $key !== 'id', ARRAY_FILTER_USE_KEY);
        return $this->categoryService->updateCategory($id, $data);
      },
    ];
  }

  /**
   * deleteCategory(id: Int!): Boolean!
   *
   * @return array<string, mixed>
   */
  public function delete(): array
  {
    return [
      'type' => Type::nonNull(Type::boolean()),
      'description' => 'Delete a category by ID.',
      'args' => [
        'id' => ['type' => Type::nonNull(Type::int())],
      ],
      'resolve' => function (mixed $root, array $args): bool {
        $this->categoryService->deleteCategory($args['id']);
        return true;
      },
    ];
  }
}