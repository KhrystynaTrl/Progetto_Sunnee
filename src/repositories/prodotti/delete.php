<?php
namespace App\repositories;

use App\models\Prodotto;
use Exception;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try{
    $db = $this->container->make("PDO");
    $prodotto = new Prodotto($db);
    $prodotto->nome = $_GET["nome"] ?? null;
    if($nome == null){
        throw new Exception("nome obbligatorio", 400);
    }
    
    if($prodotto->delete()){
        http_response_code(200);
        echo json_encode(array("risposta" => "Il prodotto è stato eliminato"));
    }else{
        http_response_code(503);
        echo json_encode(array("risposta" => "Impossibile eliminare il prodotto"));
    };
}catch(Exception $e){
    http_response_code($e->getCode());
    echo json_encode(array("message" => $e->getMessage()));
}