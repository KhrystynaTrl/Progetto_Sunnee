<?php

namespace App\repositories;
use App\models\Prodotto;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


$db = $this->container->make("PDO");
$prodotto = new Prodotto($db);
$data = json_decode(file_get_contents("php://input"));

$stmt = $prodotto->read();
$num = $stmt->rowCount();

if($num > 0) {
    $prodotti_arr = array();
    $prodotti_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $prodotto_item = array(
            "nome" => $nome,
            "kg_riciclati" => $kg_riciclati
        );
        array_push($prodotti_arr["records"], $prodotto_item);
    }
    http_response_code(200);
    echo json_encode($prodotti_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Nessun Prodotto Trovato.")
    );
}