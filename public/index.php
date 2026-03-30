<?php

// use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';


// Load .env
// $dotenv = Dotenv::createImmutable(dirname(__DIR__));
// $dotenv->load();

// // Load configs
// $doctrineConfig = require dirname(__DIR__) . '/config/doctrine.php';
// $appConfig = require dirname(__DIR__) . '/config/app.php';

// // Build the EntityManager — this is the ONE call to the factory
// $em = EntityManagerFactory::create($doctrineConfig);

// // Pass $em down into your repositories
// $userRepository = new UserRepository($em);

// // Pass repositories into your services
// $userService = new UserService($userRepository);

// // Pass services into your controller
// $controller = new GraphQLController($userService /*, other services */);




$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    // TODO: fix the public prefix before deployment
    $r->post('/public/graphql', [App\Controller\GraphQL::class, 'handle']);
    $r->get('/public/', [App\Controller\Test::class, 'test']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 not found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405 method not allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // echo "found";
        echo $handler($vars);
        break;
}