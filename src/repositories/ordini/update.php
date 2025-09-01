<?php

namespace App\repositories;
use App\models\Ordine;
use App\models\Prodotto;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$db = $this->container->make("PDO");
$ordine = new Ordine($db);
$data = json_decode(file_get_contents("php://input"));

$prodotto = new Prodotto($db);
$prodotto->nome = $data->prodotto; 
$result = $prodotto->findByNome();

$ordine->prodotto = $result->ID;
$ordine->quantita = $data->quantita;
$ordine->ID = $data->ID;

if($ordine->update()){
    http_response_code(200);
    echo json_encode(array("risposta" => "Ordine aggiornato"));
}else{
    http_response_code(503);
    echo json_encode(array("risposta" => "Impossibile aggiornare l'ordine"));
}
