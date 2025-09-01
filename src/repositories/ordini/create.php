<?php
namespace App\repositories;

use App\models\Ordine;
use DateTime;
use App\models\Prodotto;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = $this->container->make("PDO");

$ordine = new Ordine($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->prodotto) && !empty($data->quantita)){
    if(!empty($data->data_di_vendita)){
        $data_decode = DateTime::createFromFormat("d/m/Y", $data->data_di_vendita);
        $ordine->data_di_vendita = $data_decode->format("Y-m-d");
    }

    $prodotto = new Prodotto($db);
    $prodotto->nome = $data->prodotto;
    $result = $prodotto->findByNome();

    $ordine->prodotto = $result->ID;
    $ordine->quantita = $data->quantita;

    if($ordine->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Ordine creato correttamente."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Impossibile creare l'ordine."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Impossibile creare l'ordine, dati incompleti."));
}