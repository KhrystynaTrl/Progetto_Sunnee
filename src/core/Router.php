<?php

namespace Core;

class Router { 
    private $routes = []; 

    public function __construct(){}

     public function router($method, $uri, $controller){
        $this->routes[] = [
            'uri'        => $uri,
            'controller' => $controller,
            'method'     => $method
        ];
        return $this;
    }

    public function get($uri, $controller){
        $this->router('GET', $uri, $controller);
    }

    public function post($uri, $controller){
        $this->router('POST', $uri, $controller);
    }

    public function put($uri, $controller){
        $this->router('PUT', $uri, $controller);
    }

    public function delete($uri, $controller){
        $this->router('DELETE', $uri, $controller);
    }

    public function route($uri, $method){
    foreach ($this->routes as $route) {
        if ($route['uri'] === $uri && $method == $route['method']) {
            return $this->activateController(...explode('::', $route['controller']));
        }
    }
        throw new \Exception("Route not defined for this URI");
    }

    public function activateController($route, $function){
        $controller = "App\\Controllers\\{$route}";
        $controller = new $controller; 

        if (!method_exists($controller, $function)) {
            throw new \Exception("$function not defined for the controller $controller");
        }
        return $controller->$function();
    }
}

