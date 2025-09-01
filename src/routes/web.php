<?php
require_once __DIR__ . '/../core/Router.php';
use App\controllers\ProdottiController;
use App\controllers\OrdiniController;

$router->add('prodotto', new ProdottiController($container));
$router->add('ordine', new OrdiniController($container));