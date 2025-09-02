<?php

// totale dei kg di plastica riciclata, di filtrare per range temporale e per prodotto.

namespace App\repositories;
use App\models\Ordine;
use PDO;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


$db = $this->container->make("PDO");
$ordine = new Ordine($db);
$data = json_decode(file_get_contents("php://input"));

$stmt = $ordine->read();
$num = $stmt->rowCount();

if($num > 0) {
    $ordini_arr = array();
    $ordini_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $ordine_item = array(
            "data_di_vendita" => $data_di_vendita,
            "quantita" => $quantita,
            "prodotto" => $prodotto
        );
        array_push($ordini_arr["records"], $ordine_item);
    }
    http_response_code(200);
    echo json_encode($ordini_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Nessun Prodotto Trovato.")
    );
}