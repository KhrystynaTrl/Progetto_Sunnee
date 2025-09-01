<?php
namespace App\models;

class Ordine{
    private $conn;
    private $table_name = "ordine";
    public $ID;
    public $data_di_vendita;
    public $quantita;
    public $prodotto;

public function __construct($db){
        $this->conn = $db;
}

 function read() {
            $query = "SELECT data_di_vendita, prodotto, quantita FROM " . $this->table_name . "WHERE ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ID);
            $stmt->execute();

            return $stmt;
        }
    public function create(): bool{
        $query = "INSERT INTO " . $this->table_name . " SET data_di_vendita=:data_di_vendita, prodotto=:prodotto, quantita=:quantita";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":data_di_vendita", $this->data_di_vendita);
        $stmt->bindParam(":prodotto", $this->prodotto);
        $stmt->bindParam(":quantita", $this->quantita);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    function update() {
        $query = "UPDATE " . $this->table_name . " SET prodotto = :prodotto, quantita = :quantita WHERE ID = :ID";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":prodotto", $this->prodotto);
        $stmt->bindParam(":quantita", $this->quantita);
        $stmt->bindParam(":ID",$this->ID);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ID);
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}


?>