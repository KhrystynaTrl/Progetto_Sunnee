<?php
namespace App\config;

use PDO;
use PDOException;

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct(){
        $this->host = $_ENV["DB_HOST"] ?? null;
        $this-> db_name = $_ENV["DB_NAME"] ?? null;
        $this->username = $_ENV["USER"] ?? null;
        $this->password = $_ENV["PASSWORD"] ?? null;
        $this->conn = null;
    }
    
    public function getConnection() {
        if($this->conn != null){
            return $this-> conn;
        }
       
        try {
            echo $this->username." ".$this->password;
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=3307;dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Errore di connessione: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
$database = new Database();

$container->bind("PDO", function()  use($database){
    return $database->getConnection();
});
