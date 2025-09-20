<?php
namespace App\Models;

use PDO; 
use PDOException;
use Exception;
use Core\Response;

class Product{
    private $conn;
    public const TABLE_NAME = "Product";

    public function __construct($db){
        $this->conn = $db;
    }

    public function readAll(): mixed {
        try{
            $query = "SELECT `name`, kg_recycled FROM " . Product::TABLE_NAME;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException | Exception $e) {
            Response::get(500, $e -> getMessage());
        }
        return [];
    }

        
    public function findByName($name) {
        try{
            $query = "SELECT `name`, kg_recycled, ID FROM " . Product::TABLE_NAME . " WHERE `name`=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $name);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException | Exception $e) {
            Response::get(500, $e -> getMessage());
        }
        return null;
    }

    public function findById($id) {
        try{
            $query = "SELECT `name`, kg_recycled, ID FROM " . Product::TABLE_NAME . " WHERE ID=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException | Exception $e) {
            Response::get(500, $e -> getMessage());
        }
        return null;
    }
    public function create($data): bool{
        try{
            $query = "INSERT INTO " . Product::TABLE_NAME . " SET `name`=:name, kg_recycled=:kg_recycled";
            $stmt = $this->conn->prepare($query);
            $data->name = htmlspecialchars(strip_tags($data->name));
            $data->kg_recycled = htmlspecialchars(strip_tags($data->kg_recycled));
            $stmt->bindParam(":name", $data->name);
            $stmt->bindParam(":kg_recycled", $data->kg_recycled);
            $stmt->execute();
            return $stmt && $stmt->rowCount() > 0;
        } catch(PDOException | Exception $e) {
            Response::get(500,$e->getMessage());
        }
        return false;
    }
    public function update($data): bool {
        try{

            $query = "UPDATE " . Product::TABLE_NAME . " SET `name` = :name, kg_recycled = :kg_recycled WHERE ID = :ID";
            $stmt = $this->conn->prepare($query);
    
            $data->name = htmlspecialchars(strip_tags($data->name));
            $data->kg_recycled = htmlspecialchars(strip_tags($data->kg_recycled));
            
            $stmt->bindParam(":name", $data->name);
            $stmt->bindParam(":kg_recycled", $data->kg_recycled);
            $stmt->bindParam(":ID",$data->ID);

            $stmt->execute();
            
            return $stmt && $stmt->rowCount() > 0;
        } catch(PDOException | Exception $e) {
            Response::get(500,$e->getMessage());
        }
        return false;

    }
    public function delete($name): bool {
        try{
            $query = "DELETE FROM " . Product::TABLE_NAME . " WHERE `name` = ?";
            $stmt = $this->conn->prepare($query);
            $name = htmlspecialchars(strip_tags($name));
            $stmt->bindParam(1, $name);
            $stmt->execute();

            return $stmt && $stmt->rowCount() > 0;
        } catch(PDOException | Exception $e) {
            Response::get(500,$e->getMessage());
        }
        return false;
    }
}