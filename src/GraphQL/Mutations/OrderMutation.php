<?php

namespace App\GraphQL\Mutations;

use App\GraphQL\Types\OrderType;
use App\Services\OrderService;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class OrderMutation
{
  private InputObjectType $orderItemInputType;

  public function __construct(
    private readonly OrderService $orderService,
    private readonly OrderType $orderType,
  ) {
    // Inline input type for order items passed during order creation.
    // Defined once here so both create() and the schema share the same instance.
    $this->orderItemInputType = new InputObjectType([
      'name' => 'OrderItemInput',
      'description' => 'A product and quantity to include in an order.',
      'fields' => [
        'productId' => ['type' => Type::nonNull(Type::int())],
        'quantity' => ['type' => Type::nonNull(Type::int())],
      ],
    ]);
  }

  /**
   * createOrder(items: [OrderItemInput!]!): Order!
   *
   * @return array<string, mixed>
   */
  public function create(): array
  {
    return [
      'type' => Type::nonNull($this->orderType),
      'description' => 'Place a new order.',
      'args' => [
        'items' => [
          'type' => Type::nonNull(Type::listOf(Type::nonNull($this->orderItemInputType))),
          'description' => 'List of products and quantities to order.',
        ],
      ],
      'resolve' => fn(mixed $root, array $args) =>
        $this->orderService->createOrder($args['items']),
    ];
  }

  /**
   * updateOrderStatus(id: Int!, status: String!): Order!
   *
   * @return array<string, mixed>
   */
  public function updateStatus(): array
  {
    return [
      'type' => Type::nonNull($this->orderType),
      'description' => 'Update the status of an existing order.',
      'args' => [
        'id' => ['type' => Type::nonNull(Type::int())],
        'status' => [
          'type' => Type::nonNull(Type::string()),
          'description' => 'New status: pending, confirmed, shipped, delivered, cancelled.',
        ],
      ],
      'resolve' => fn(mixed $root, array $args) =>
        $this->orderService->updateOrderStatus($args['id'], $args['status']),
    ];
  }

  /**
   * cancelOrder(id: Int!): Boolean!
   *
   * @return array<string, mixed>
   */
  public function cancel(): array
  {
    return [
      'type' => Type::nonNull(Type::boolean()),
      'description' => 'Cancel an order. Returns true on success.',
      'args' => [
        'id' => ['type' => Type::nonNull(Type::int())],
      ],
      'resolve' => function (mixed $root, array $args): bool {
        $this->orderService->cancelOrder($args['id']);
        return true;
      },
    ];
  }
}