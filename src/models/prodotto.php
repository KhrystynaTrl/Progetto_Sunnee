<?php
namespace App\models;

use PDO; 
class Prodotto{
    private $conn;
    private $table_name = "prodotto";

    public $ID;
    public $nome;
    public $kg_riciclati;

    public function __construct($db){
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT nome, kg_riciclati FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

        
    function findByNome() {
        $query = "SELECT nome, kg_riciclati, ID FROM " . $this->table_name . " WHERE nome=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->nome);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $result = null;
        if($row){
            $result = new Prodotto(null);
            foreach($row as $key => $value){
                $result->$key = $value;
            }
        }
        return $result;
    }
    public function create(): bool{
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome, kg_riciclati=:kg_riciclati";
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->kg_riciclati = htmlspecialchars(strip_tags($this->kg_riciclati));
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":kg_riciclati", $this->kg_riciclati);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    function update() {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, kg_riciclati = :kg_riciclati WHERE ID = :ID";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->kg_riciclati = htmlspecialchars(strip_tags($this->kg_riciclati));
        
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":kg_riciclati", $this->kg_riciclati);
        $stmt->bindParam(":ID",$this->ID);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE nome = ?";
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $stmt->bindParam(1, $this->nome);
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}