<?php

namespace App\GraphQL;

use App\GraphQL\Mutations\CategoryMutation;
use App\GraphQL\Mutations\OrderMutation;
use App\GraphQL\Mutations\ProductMutation;
use App\GraphQL\Queries\CategoryQuery;
use App\GraphQL\Queries\OrderQuery;
use App\GraphQL\Queries\ProductQuery;
use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\OrderItemType;
use App\GraphQL\Types\OrderType;
use App\GraphQL\Types\PaginatedOrdersType;
use App\GraphQL\Types\PaginatedProductsType;
use App\GraphQL\Types\ProductType;
use App\Services\CategoryService;
use App\Services\OrderService;
use App\Services\ProductService;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema as GraphQLSchema;
use GraphQL\Type\SchemaConfig;

class Schema
{
    // -------------------------------------------------------------------------
    // Types (built once, shared across queries and mutations)
    // -------------------------------------------------------------------------
    private CategoryType $categoryType;
    private ProductType $productType;
    private OrderType $orderType;

    // -------------------------------------------------------------------------
    // Queries
    // -------------------------------------------------------------------------
    private CategoryQuery $categoryQuery;
    private ProductQuery $productQuery;
    private OrderQuery $orderQuery;

    // -------------------------------------------------------------------------
    // Mutations
    // -------------------------------------------------------------------------
    private CategoryMutation $categoryMutation;
    private ProductMutation $productMutation;
    private OrderMutation $orderMutation;

    public function __construct(
        CategoryService $categoryService,
        ProductService $productService,
        OrderService $orderService,
    ) {
        // 1. Build types bottom-up (no forward references needed)
        $this->categoryType = new CategoryType();
        $this->productType = new ProductType($this->categoryType);
        $this->orderType = new OrderType($this->orderItemType);
        // 2. Build queries, injecting their required types
        $this->categoryQuery = new CategoryQuery($categoryService, $this->categoryType);
        $this->productQuery = new ProductQuery($productService, $this->productType, $this->paginatedProductsType);
        $this->orderQuery = new OrderQuery($orderService, $this->orderType, $this->paginatedOrdersType);

        // 3. Build mutations, injecting their required types
        $this->categoryMutation = new CategoryMutation($categoryService, $this->categoryType);
        $this->productMutation = new ProductMutation($productService, $this->productType);
        $this->orderMutation = new OrderMutation($orderService, $this->orderType);
    }

    /**
     * Build and return the executable GraphQL schema.
     */
    public function build(): GraphQLSchema
    {
        return new GraphQLSchema(
            SchemaConfig::create()
                ->setQuery($this->buildQueryType())
                ->setMutation($this->buildMutationType())
        );
    }

    // -------------------------------------------------------------------------
    // Root types
    // -------------------------------------------------------------------------

    private function buildQueryType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => [
                // Category
                'category' => $this->categoryQuery->getOne(),
                'categories' => $this->categoryQuery->getAll(),

                // Product
                'product' => $this->productQuery->getOne(),
                'products' => $this->productQuery->getAll(),

                // Order
                'order' => $this->orderQuery->getOne(),
                'orders' => $this->orderQuery->getAll(),
            ],
        ]);
    }

    private function buildMutationType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                // Category
                'createCategory' => $this->categoryMutation->create(),
                'updateCategory' => $this->categoryMutation->update(),
                'deleteCategory' => $this->categoryMutation->delete(),

                // Product
                'createProduct' => $this->productMutation->create(),
                'updateProduct' => $this->productMutation->update(),
                'deleteProduct' => $this->productMutation->delete(),

                // Order
                'createOrder' => $this->orderMutation->create(),
                'updateOrderStatus' => $this->orderMutation->updateStatus(),
                'cancelOrder' => $this->orderMutation->cancel(),
            ],
        ]);
    }
}