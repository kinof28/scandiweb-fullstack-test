<?php

declare(strict_types=1);

return [

  /*
  |--------------------------------------------------------------------------
  | Database Connection
  |--------------------------------------------------------------------------
  */
  'connection' => [
    'driver' => 'pdo_mysql',
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
    'dbname' => $_ENV['DB_DATABASE'] ?? 'myapp',
    'user' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
  ],

  /*
  |--------------------------------------------------------------------------
  | Entity Mapping
  |--------------------------------------------------------------------------
  | 'attribute'  — PHP 8 #[Entity] attributes (recommended)
  | 'annotation' — legacy @Entity docblock annotations
  | 'xml'        — XML mapping files
  */
  'mapping' => [
    'driver' => 'attribute',
    'paths' => [
      dirname(__DIR__) . '/src/Models',
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Migrations
  |--------------------------------------------------------------------------
  */
  'migrations' => [
    'directory' => dirname(__DIR__) . '/database/migrations',
    'namespace' => 'Database\\Migrations',
    'table' => 'doctrine_migration_versions',
    'column' => 'version',
  ],

  /*
  |--------------------------------------------------------------------------
  | Cache
  |--------------------------------------------------------------------------
  | In development, ArrayAdapter is used (no caching).
  | In production, switch to FilesystemAdapter or a Redis/APCu adapter
  | for metadata and query result caching.
  */
  'cache' => [
    'dir' => dirname(__DIR__) . '/var/cache/doctrine',
  ],

  /*
  |--------------------------------------------------------------------------
  | Proxy Classes
  |--------------------------------------------------------------------------
  | Doctrine generates proxy classes for lazy-loading entities.
  | Set auto_generate to false in production and pre-generate them via CLI:
  |   vendor/bin/doctrine orm:generate-proxies
  */
  'proxy' => [
    'dir' => dirname(__DIR__) . '/var/cache/doctrine/proxies',
    'namespace' => 'Proxies',
    'auto_generate' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
  ],

  /*
  |--------------------------------------------------------------------------
  | Development Mode
  |--------------------------------------------------------------------------
  | When true, schema validation and SQL logging are enabled.
  | Driven by APP_ENV so you never accidentally enable it in production.
  */
  'dev_mode' => ($_ENV['APP_ENV'] ?? 'production') === 'development',

];