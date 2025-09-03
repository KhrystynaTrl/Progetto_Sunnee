<?php
namespace App\models;
use PDOException;
use Exception;

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
            $query = "SELECT data_di_vendita, prodotto, quantita FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;
        }
    public function create(): bool{
        try{
            $query = "INSERT INTO " . $this->table_name . " SET data_di_vendita=:data_di_vendita, prodotto=:prodotto, quantita=:quantita";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":data_di_vendita", $this->data_di_vendita);
            $stmt->bindParam(":prodotto", $this->prodotto);
            $stmt->bindParam(":quantita", $this->quantita);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return true; 
            } else {
                return false;
            }
        } catch(PDOException | Exception $e) {
            return false;
        }
    }

    function update() {
        try{       
            $query = "UPDATE " . $this->table_name . " SET prodotto = :prodotto, quantita = :quantita WHERE ID = :ID";
            $stmt = $this->conn->prepare($query);
    
            $stmt->bindParam(":prodotto", $this->prodotto);
            $stmt->bindParam(":quantita", $this->quantita);
            $stmt->bindParam(":ID",$this->ID);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true; 
            } else {
                return false;
            }

        } catch(PDOException | Exception $e) {
            return false;
        }
    }

    function delete() {
        try{
            $query = "DELETE FROM " . $this->table_name . " WHERE ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ID);
             $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true; 
            } else {
                return false;
            }

        } catch(PDOException | Exception $e) {
            return false;
        }
    }
    function search($dataFrom, $dataTo, $prodotto){
        $query = "SELECT round(sum(p.kg_riciclati * o.quantita),2) AS tot_kg_riciclati, p.nome FROM prodotto p 
        INNER JOIN ordine o on p.id = o.prodotto WHERE o.id = o.id ";
    
        $dataFromQuery = "AND o.data_di_vendita >= :dataFrom ";
        $dataToQuery = "AND o.data_di_vendita < :dataTo ";
        $prodottoQuery = "AND p.nome= :prodotto";
        
        if($dataFrom){
            $query .= $dataFromQuery;
        }
        if($dataTo){
            $query .= $dataToQuery;
        }
        if($prodotto){
            $query .= $prodottoQuery;
        }
        
        $query.=" GROUP BY p.nome";
        $stmt = $this->conn->prepare($query);

        if($dataFrom){
            $stmt->bindParam(":dataFrom", $dataFrom);
        }
        if($dataTo){
            $stmt->bindParam(":dataTo", $dataTo);
        }
        if($prodotto){
            $stmt->bindParam(":prodotto", $prodotto);
        }

        if($stmt->execute()) {
            return $stmt;
        }
        return null;
        
    }
}




?>