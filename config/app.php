<?php

declare(strict_types=1);

return [

  /*
  |--------------------------------------------------------------------------
  | Application
  |--------------------------------------------------------------------------
  */
  'name' => $_ENV['APP_NAME'] ?? 'MyApp',
  'env' => $_ENV['APP_ENV'] ?? 'production',  // 'development' | 'production'
  'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
  'url' => $_ENV['APP_URL'] ?? 'http://localhost',
  'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
  'locale' => $_ENV['APP_LOCALE'] ?? 'en',

  /*
  |--------------------------------------------------------------------------
  | GraphQL
  |--------------------------------------------------------------------------
  */
  'graphql' => [
    'endpoint' => '/graphql',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'max_query_depth' => (int) ($_ENV['GRAPHQL_MAX_DEPTH'] ?? 10),
    'max_complexity' => (int) ($_ENV['GRAPHQL_MAX_COMPLEXITY'] ?? 200),
  ],

  /*
  |--------------------------------------------------------------------------
  | CORS
  |--------------------------------------------------------------------------
  */
  'cors' => [
    'allowed_origins' => explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? '*'),
    'allowed_methods' => ['GET', 'POST', 'OPTIONS'],
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
    'expose_headers' => [],
    'max_age' => 86400,
    'allow_credentials' => filter_var($_ENV['CORS_ALLOW_CREDENTIALS'] ?? false, FILTER_VALIDATE_BOOLEAN),
  ],

  /*
  |--------------------------------------------------------------------------
  | Authentication / JWT
  |--------------------------------------------------------------------------
  */
  'auth' => [
    'jwt_secret' => $_ENV['JWT_SECRET'] ?? '',
    'jwt_expiration' => (int) ($_ENV['JWT_EXPIRATION'] ?? 3600), // seconds
    'jwt_algorithm' => $_ENV['JWT_ALGORITHM'] ?? 'HS256',
  ],

  /*
  |--------------------------------------------------------------------------
  | Logging
  |--------------------------------------------------------------------------
  */
  'logging' => [
    'level' => $_ENV['LOG_LEVEL'] ?? 'warning', // debug|info|warning|error
    'channel' => $_ENV['LOG_CHANNEL'] ?? 'file',    // file|stderr
    'path' => dirname(__DIR__) . '/var/logs/app.log',
  ],

];