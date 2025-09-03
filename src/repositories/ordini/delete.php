<?php

namespace App\repositories;

use App\models\Ordine;
use Exception;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    $db = $this->container->make("PDO");
    $ordine = new Ordine($db);
    $ordine->ID = $_GET["ID"] ?? null;
    if($ordine->ID == null){
        throw new Exception("Id obbligatorio", 400);
    }
    if($ordine->delete()){
        http_response_code(200);
        echo json_encode(array("risposta" => "L'ordine è stato eliminato"));
    }else{
        http_response_code(503);
        echo json_encode(array("risposta" => "Impossibile eliminare l'ordine"));
    };
    
} catch(Exception $e) {
    http_response_code($e->getCode());
    echo json_encode(array("message" => $e->getMessage()));
}