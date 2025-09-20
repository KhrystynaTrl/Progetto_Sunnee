<?php

use App\config\Container;
use App\config\Database;
use Core\Router;

$container = Container::getInstance();

$container->bind(Router::class, function($c) {
    $router = new Router();
    $loadRoutes = require __DIR__ . '/../routes/web.php';
    $loadRoutes($router);
    return $router;
});

$container->bind(PDO::class, function($c){
    return (new Database())->getConnection();
});

return $container;
