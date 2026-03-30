<?php

namespace App\GraphQL\Types;

use App\Models\Category;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CategoryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Category',
            'description' => 'A product category.',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => fn(Category $category) => $category->getId(),
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn(Category $category) => $category->getName(),
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => fn(Category $category) => $category->getDescription(),
                ],
                'createdAt' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn(Category $category) => $category->getCreatedAt()->format(DATE_ATOM),
                ],
                'updatedAt' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn(Category $category) => $category->getUpdatedAt()->format(DATE_ATOM),
                ],
            ],
        ]);
    }
}