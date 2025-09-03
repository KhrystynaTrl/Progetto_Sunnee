<?php
namespace App\repositories;
use App\models\Ordine;
use Exception;
use PDO;
use DateTime;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

try{
    $db = $this->container->make("PDO");
    $ordine = new Ordine($db);
    $data = json_decode(file_get_contents("php://input"));
    
    $dataFromDb = ""; 
    if(!empty($data->dataFrom)){
        $data_decode = DateTime::createFromFormat("d/m/Y", $data->dataFrom);
        $dataFromDb = $data_decode->format("Y-m-d");
    }
    $dataToDb = "";
    if(!empty($data->dataTo)){
        $data_decode = DateTime::createFromFormat("d/m/Y", $data->dataTo);
        $dataToDb = $data_decode->format("Y-m-d");
    }
    $prodottoDb = "";
    if(!empty($data->prodotto)){
        $prodottoDb = $data->prodotto;
    }
    
    $stmt = $ordine->search($dataFromDb, $dataToDb, $prodottoDb);
    $num = $stmt->rowCount();
    
    if($num > 0) {
        $ordini_arr = array();
        $ordini_arr["records"] = array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $ordine_item = array(
                "tot_kg_riciclati" => $tot_kg_riciclati,
                "nome" => $nome
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
}catch(Exception $e){
    http_response_code(500);
    echo json_encode(array("message" => $e->getMessage()));
}