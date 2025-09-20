<?php

return function ($router){
    $router->get('product/all', 'ProductController::readAll');
    $router->get('product', 'ProductController::read');
    $router->post('product', 'ProductController::create');
    $router->put('product', 'ProductController::update');
    $router->delete('product', 'ProductController::delete');
    
    $router->get('order/all', 'OrderController::readAll');
    $router->get('order', 'OrderController::read');
    $router->post('order', 'OrderController::create');
    $router->put('order', 'OrderController::update');
    $router->delete('order', 'OrderController::delete');
    $router->post('order/search','OrderController::search');
};
