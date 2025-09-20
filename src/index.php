<?php

require_once __DIR__. '/../vendor/autoload.php';
use Dotenv\Dotenv;
use Core\Request;

$dotenv = Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$container = require __DIR__ . '/core/bootstrap.php';

$router = $container->make(\Core\Router::class);

$router->route(Request::uri(),Request::method());