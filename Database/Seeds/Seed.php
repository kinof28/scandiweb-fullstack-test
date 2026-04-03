<?php

namespace Database\Seeds;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Database\EntityManagerFactory;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();


$doctrineConfig = require dirname(__DIR__, 2) . '/config/doctrine.php';

$em = EntityManagerFactory::create($doctrineConfig);

$seeder = new DatabaseSeeder($em);

$seeder->run(__DIR__ . '/data.json');