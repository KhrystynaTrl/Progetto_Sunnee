<?php

namespace App\controllers;

class OrdiniController {

    private $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function handle() {
        $method = $_SERVER["REQUEST_METHOD"];
        switch($method){
            case "POST" : 
                $uri = trim($_SERVER['REQUEST_URI'], '/');
                $secondPart = explode("/",$uri)[1] ?? null;
                if($secondPart && $secondPart === "ricerca"){
                    require __DIR__ . "/../repositories/ordini/search.php";
                } else {
                    require __DIR__."/../repositories/ordini/create.php";
                }
            break;
            case "DELETE" : require __DIR__."/../repositories/ordini/delete.php";
            break;
            case "PUT" : require __DIR__ ."/../repositories/ordini/update.php";
            break;
            case "GET" : require __DIR__ . "/../repositories/ordini/read.php";
            break;
        }
    
    }
}