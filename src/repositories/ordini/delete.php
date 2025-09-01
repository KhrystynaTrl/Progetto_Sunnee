<?php

namespace App\repositories;

use App\models\Ordine;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = $this->container->make("PDO");
$ordine = new Ordine($db);
$ordine->ID = $_GET["ID"];

if($ordine->delete()){
    http_response_code(200);
    echo json_encode(array("risposta" => "L'ordine è stato eliminato"));
}else{
    http_response_code(503);
    echo json_encode(array("risposta" => "Impossibile eliminare l'ordine"));
};