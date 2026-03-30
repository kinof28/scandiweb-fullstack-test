<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\Types\CategoryType;
use App\Services\CategoryService;
use GraphQL\Type\Definition\Type;

class CategoryQuery
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly CategoryType $categoryType,
    ) {
    }

    /**
     * category(id: Int!): Category
     *
     * @return array<string, mixed>
     */
    public function getOne(): array
    {
        return [
            'type' => $this->categoryType,
            'description' => 'Fetch a single category by ID.',
            'args' => [
                'id' => ['type' => Type::nonNull(Type::int())],
            ],
            'resolve' => fn(mixed $root, array $args) =>
                $this->categoryService->getCategoryById($args['id']),
        ];
    }

    /**
     * categories: [Category!]!
     *
     * @return array<string, mixed>
     */
    public function getAll(): array
    {
        return [
            'type' => Type::nonNull(Type::listOf(Type::nonNull($this->categoryType))),
            'description' => 'Fetch all categories.',
            'resolve' => fn(mixed $root, array $args) =>
                $this->categoryService->getAllCategories(),
        ];
    }
}