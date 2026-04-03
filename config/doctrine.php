<?php

declare(strict_types=1);

return [

  'connection' => [
    'driver' => 'pdo_mysql',
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
    'dbname' => $_ENV['DB_DATABASE'] ?? 'myapp',
    'user' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
  ],

  'mapping' => [
    'driver' => 'attribute',
    'paths' => [
      dirname(__DIR__) . '/src/Models',
    ],
  ],
];