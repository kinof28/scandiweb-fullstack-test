<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Order;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderType extends ObjectType
{
  public function __construct(OrderItemType $orderItemType)
  {
    parent::__construct([
      'name' => 'Order',
      'description' => 'A customer order.',
      'fields' => [
        'id' => [
          'type' => Type::nonNull(Type::int()),
          'resolve' => fn(Order $order) => $order->getId(),
        ],
        'status' => [
          'type' => Type::nonNull(Type::string()),
          'description' => 'Order status: pending, confirmed, shipped, delivered, cancelled.',
          'resolve' => fn(Order $order) => $order->getStatus(),
        ],
        'items' => [
          'type' => Type::nonNull(Type::listOf(Type::nonNull($orderItemType))),
          'resolve' => fn(Order $order) => $order->getItems()->toArray(),
        ],
        'total' => [
          'type' => Type::nonNull(Type::float()),
          'description' => 'Sum of all item subtotals.',
          'resolve' => fn(Order $order) => $order->getTotal(),
        ],
        'createdAt' => [
          'type' => Type::nonNull(Type::string()),
          'resolve' => fn(Order $order) => $order->getCreatedAt()->format(DATE_ATOM),
        ],
        'updatedAt' => [
          'type' => Type::nonNull(Type::string()),
          'resolve' => fn(Order $order) => $order->getUpdatedAt()->format(DATE_ATOM),
        ],
      ],
    ]);
  }
}