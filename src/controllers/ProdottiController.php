<?php

namespace App\controllers;

class ProdottiController {

    private $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function handle() {
        $method = $_SERVER["REQUEST_METHOD"];
        switch($method){
            case "POST" : require __DIR__."/../repositories/prodotti/create.php";
            break;
            case "DELETE" : require __DIR__."/../repositories/prodotti/delete.php";
            break;
            case "PUT" : require __DIR__ ."/../repositories/prodotti/update.php";
            break;
            case "GET" : require __DIR__ . "/../repositories/prodotti/read.php";
            break;
        }
        
    }
}