<?php

namespace Core;

class Response
{
    public static function get($responseCode, $data)
    {
        http_response_code($responseCode);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
        exit();
    }
}

?>