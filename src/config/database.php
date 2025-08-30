<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct(){
    $this->host = $__ENV["DB_HOST"];
    $this-> db_name = $__ENV["DB_NAME"];
    $this->username = $__ENV["USER"];
    $this->password = $__ENV["PASSWORD"];
    }
    
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Errore di connessione: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
