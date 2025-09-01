<?php

require_once __DIR__. '/../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();
require_once __DIR__ . '/config/container.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/routes/web.php';




$uri = trim($_SERVER['REQUEST_URI'], '/'); 

$router->dispatch($uri);