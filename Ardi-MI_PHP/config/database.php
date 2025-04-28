<?php
class Database {
  private static $instance = null;
  private $connection;
  
  private function __construct() {
    $host = 'localhost';
    $dbname = 'ardi_mi';
    $username = 'root';
    $password = '';
    
    try {
      $this->connection = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8", 
        $username, 
        $password
      );
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      die("Connection failed: " . $e->getMessage());
    }
  }
  
  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new Database();
    }
    return self::$instance->connection;
  }
  
  // Evitar la clonación del objeto
  private function __clone() {}
}
?>