<?php
namespace App\config;

use Exception;
class Container { 
    protected $bindings = [];
    public function bind($abstract, $concrete) {
        $this->bindings[$abstract] = $concrete;
    }

    public function make($abstract) {
        if (!isset($this->bindings[$abstract])) {
            throw new Exception("No binding found for {$abstract}");
        }

        return call_user_func($this->bindings[$abstract],$this);
    }
}


$container = new Container();
?>