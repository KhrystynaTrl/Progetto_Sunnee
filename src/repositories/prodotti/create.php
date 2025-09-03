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
    $data = json_decode(file_get_contents("php://input"));
    
    if(!empty($data->nome) && !empty($data->kg_riciclati)){
        $prodotto->nome = $data->nome;
        $prodotto->kg_riciclati = $data->kg_riciclati;
        if($prodotto->create()){
            http_response_code(201);
            echo json_encode(array("message" => "Prodotto creato correttamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossibile creare il Prodotto."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Impossibile creare il prodotto, dati incompleti."));
    };
}catch(Exception $e){
    http_response_code(500);
    echo json_encode(array("message" => $e->getMessage()));
};
