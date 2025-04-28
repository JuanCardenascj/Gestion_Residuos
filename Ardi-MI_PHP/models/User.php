<?php
class User {
  private $db;

  public function __construct() {
    $this->db = Database::getInstance();
  }

  public function authenticate($email, $password) {
    $stmt = $this->db->prepare("
      SELECT * FROM users 
      WHERE email = ? AND status = 'active'
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
      unset($user['password']);
      return $user;
    }
    return false;
  }

  public function create($data) {
    $stmt = $this->db->prepare("
      INSERT INTO users 
      (name, email, password, phone, role, whatsapp, status) 
      VALUES (?, ?, ?, ?, ?, ?, 'active')
    ");
    
    return $stmt->execute([
      $data['name'],
      $data['email'],
      $data['password'],
      $data['phone'],
      $data['role'],
      $data['whatsapp'] ? 1 : 0
    ]);
  }

  public function getAllUsers() {
    $stmt = $this->db->prepare("
      SELECT * FROM users 
      WHERE role = 'user'
      ORDER BY name ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getAllCompanies() {
    $stmt = $this->db->prepare("
      SELECT * FROM users 
      WHERE role = 'company'
      ORDER BY name ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
?>