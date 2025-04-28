<?php
class Vehicle {
  private $db;

  public function __construct() {
    $this->db = Database::getInstance();
  }

  public function getCompanyVehicles($companyId) {
    $stmt = $this->db->prepare("
      SELECT * FROM vehicles 
      WHERE company_id = ? 
      ORDER BY status DESC, plate ASC
    ");
    $stmt->execute([$companyId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function add($data) {
    $stmt = $this->db->prepare("
      INSERT INTO vehicles 
      (company_id, plate, brand, model, capacity, type, status) 
      VALUES (?, ?, ?, ?, ?, ?, 'active')
    ");
    
    return $stmt->execute([
      $data['company_id'],
      $data['plate'],
      $data['brand'],
      $data['model'],
      $data['capacity'],
      $data['type']
    ]);
  }

  public function update($id, $data) {
    $stmt = $this->db->prepare("
      UPDATE vehicles 
      SET plate = ?, brand = ?, model = ?, capacity = ?, type = ?
      WHERE id = ?
    ");
    
    return $stmt->execute([
      $data['plate'],
      $data['brand'],
      $data['model'],
      $data['capacity'],
      $data['type'],
      $id
    ]);
  }

  public function toggleStatus($id) {
    $stmt = $this->db->prepare("
      UPDATE vehicles 
      SET status = IF(status = 'active', 'inactive', 'active')
      WHERE id = ?
    ");
    return $stmt->execute([$id]);
  }
}
?>