<?php
namespace App\Models;
use PDOException;
use Exception;
use Core\Response;

class Order{
    private $conn;
    public const TABLE_NAME = "`Order`";

    public function __construct($db){
            $this->conn = $db;
    }

    public function readAll(): mixed {
        try{
            $query = "SELECT O.date_of_sale, P.name as product, O.quantity FROM " . Order::TABLE_NAME ." as O ".
                     "INNER JOIN ".Product::TABLE_NAME." as P ON P.ID = O.product ";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(PDOException | Exception $e) {
            Response::get(500, $e -> getMessage());
        }
        return [];
    }

    public function read($id): mixed{
        try{
            $query = "SELECT O.date_of_sale, P.name as product, O.quantity FROM " . Order::TABLE_NAME ." as O ".
                      "INNER JOIN ".Product::TABLE_NAME." as P ON P.ID = O.product ".
                      "WHERE O.ID=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(PDOException | Exception $e) {
            Response::get(500, $e -> getMessage());
        }
        return null;
    }

    public function create($data): bool{
        try{
            $query = "INSERT INTO " . Order::TABLE_NAME . " SET ".(!empty($data->date_of_sale) ? "date_of_sale=:date_of_sale,":"") . "product=:product, quantity=:quantity";
            $stmt = $this->conn->prepare($query);
            if(!empty($data->date_of_sale)){
                $stmt->bindParam(":date_of_sale", $data->date_of_sale);
            }
            $stmt->bindParam(":product", $data->product_id);
            $stmt->bindParam(":quantity", $data->quantity);
            $stmt->execute();
    
            return $stmt && $stmt->rowCount() > 0;
        } catch(PDOException | Exception $e) {
            Response::get(500, $e -> getMessage());
        }
        return false;
    }

    public function update($data): bool {
        try{       
            $query = "UPDATE " . Order::TABLE_NAME . " SET product = :product, quantity = :quantity WHERE ID = :ID";
            $stmt = $this->conn->prepare($query);
    
            $stmt->bindParam(":product", $data->product_id);
            $stmt->bindParam(":quantity", $data->quantity);
            $stmt->bindParam(":ID",$data->ID);
            $stmt->execute();

            return $stmt && $stmt->rowCount() > 0;
        } catch(PDOException | Exception $e) {
            Response::get(500,$e->getMessage());
        }
        return false;
    }

    public function delete($id): bool {
        try{
            $query = "DELETE FROM " . Order::TABLE_NAME . " WHERE ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();

            return $stmt && $stmt->rowCount() > 0;
        } catch(PDOException | Exception $e) {
            Response::get(500, $e->getMessage());
        }
        return false;
    }
    public function search($searchData): mixed{
        try{
            $query = "SELECT round(sum(p.kg_recycled * o.quantity),2) AS tot_kg_recycled, p.name 
                      FROM ". Product::TABLE_NAME." p 
                      INNER JOIN ".Order::TABLE_NAME." o ON p.id = o.product 
                      WHERE 1=1 ";
    
            if(!empty($searchData->dateFrom)){
                $query .= " AND o.date_of_sale >= :dateFrom";
            }
            if(!empty($searchData->dateTo)){
                $query .= " AND o.date_of_sale < :dateTo";
            }
            if(!empty($searchData->product)){
                $query .= " AND p.name= :product";
            }
            
            $query .= " GROUP BY p.name";
    
            $stmt = $this->conn->prepare($query);
    
             if(!empty($searchData->dateFrom)){
                $stmt->bindValue(":dateFrom", $searchData->dateFrom);
            }
            if(!empty($searchData->dateTo)){
                $stmt->bindValue(":dateTo", $searchData->dateTo);
            }
            if(!empty($searchData->product)){
                $stmt->bindValue(":product", $searchData->product);
            }

            $stmt -> execute();
    
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(PDOException | Exception $e) {
            Response::get(500, $e->getMessage());
        }
        return [];
    }

}




?>