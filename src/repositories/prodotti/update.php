<?php
namespace App\repositories;
use App\models\Prodotto;
use Exception;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    $db = $this->container->make("PDO");
    $prodotto = new Prodotto($db);
    $data = json_decode(file_get_contents("php://input"));
    
    $prodotto->nome = $data->nome ?? null;
    $prodotto->kg_riciclati = $data->kg_riciclati ?? null;
    $prodotto->ID = $data->ID ?? null;
    
    if($prodotto->update()){
        http_response_code(200);
        echo json_encode(array("risposta" => "Prodotto aggiornato"));
    }else{
        http_response_code(503);
        echo json_encode(array("risposta" => "Impossibile aggiornare il prodotto"));
    }

}catch(Exception $e) {
    http_response_code(500);
    echo "ERRORE".$e->getMessage();
}
