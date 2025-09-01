<?php
require_once __DIR__ . '/../core/Router.php';
use App\controllers\ProdottiController;

$router->add('prodotto', new ProdottiController($container));
