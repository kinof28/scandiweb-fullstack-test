<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\Types\OrderType;
use App\GraphQL\Types\PaginatedOrdersType;
use App\Services\OrderService;
use GraphQL\Type\Definition\Type;

class OrderQuery
{
  public function __construct(
    private readonly OrderService $orderService,
    private readonly OrderType $orderType,
  ) {
  }

  /**
   * order(id: Int!): Order
   *
   * @return array<string, mixed>
   */
  public function getOne(): array
  {
    return [
      'type' => $this->orderType,
      'description' => 'Fetch a single order by ID.',
      'args' => [
        'id' => ['type' => Type::nonNull(Type::int())],
      ],
      'resolve' => fn(mixed $root, array $args) =>
        $this->orderService->getOrderById($args['id']),
    ];
  }

  /**
   * orders(limit: Int, offset: Int, status: String): PaginatedOrders!
   *
   * @return array<string, mixed>
   */
  public function getAll(): array
  {
    return [
      'type' => Type::nonNull($this->paginatedOrdersType),
      'description' => 'Fetch a paginated list of orders, optionally filtered by status.',
      'args' => [
        'limit' => [
          'type' => Type::int(),
          'defaultValue' => 20,
        ],
        'offset' => [
          'type' => Type::int(),
          'defaultValue' => 0,
        ],
        'status' => [
          'type' => Type::string(),
          'description' => 'Filter by status: pending, confirmed, shipped, delivered, cancelled.',
        ],
      ],
      'resolve' => fn(mixed $root, array $args) =>
        $this->orderService->getPaginatedOrders(
          $args['limit'],
          $args['offset'],
          $args['status'] ?? null,
        ),
    ];
  }
}