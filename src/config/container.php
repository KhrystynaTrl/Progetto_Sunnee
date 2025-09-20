<?php
namespace App\config;

use Exception;
class Container { 
    protected $bindings = [];
    private static $instance;

    private function __construct(){}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }
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