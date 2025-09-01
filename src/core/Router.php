<?php
class Router { 
    private $routes = []; 

    public function add($ruote,$controller){
        $this ->routes[$ruote] = $controller;
    }

    public function addForm($route, $callback) {
        $this->routes[$route] = $callback;
    }
    public function dispatch($url){ 
        if(array_key_exists($url, $this ->routes)){
            $controller = $this ->routes[$url];
            $controller->handle();
        } else {
            echo "404 - Pagina non trovata";
        }
    }
}

$router = new Router();
