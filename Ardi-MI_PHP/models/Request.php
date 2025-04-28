<?php
class Requests {
  private $db;

  public function __construct() {
    $this->db = Database::getInstance();
  }

  public function create($data) {
    $stmt = $this->db->prepare("
      INSERT INTO collection_requests 
      (user_id, date, type, weight, points, status) 
      VALUES (?, ?, ?, ?, ?, 'pending')
    ");
    
    return $stmt->execute([
      $data['user_id'],
      $data['date'],
      $data['type'],
      $data['weight'],
      $data['points']
    ]);
  }

  public function getUserRequests($userId) {
    $stmt = $this->db->prepare("
      SELECT * FROM collection_requests 
      WHERE user_id = ? 
      ORDER BY date DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getPendingRequests() {
    $stmt = $this->db->prepare("
      SELECT cr.*, u.name as user_name 
      FROM collection_requests cr
      JOIN users u ON cr.user_id = u.id
      WHERE cr.status = 'pending'
      ORDER BY cr.date ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function acceptRequest($requestId, $companyId, $vehicleId) {
    $stmt = $this->db->prepare("
      UPDATE collection_requests 
      SET status = 'accepted', company_id = ?, vehicle_id = ?
      WHERE id = ? AND status = 'pending'
    ");
    return $stmt->execute([$companyId, $vehicleId, $requestId]);
  }

  public function completeRequest($requestId, $weight) {
    // Recalcular puntos con el peso real
    $request = $this->getRequestById($requestId);
    $points = $this->calculatePoints($request['type'], $weight);
    
    $stmt = $this->db->prepare("
      UPDATE collection_requests 
      SET status = 'completed', weight = ?, points = ?
      WHERE id = ? AND status = 'accepted'
    ");
    return $stmt->execute([$weight, $points, $requestId]);
  }

  private function calculatePoints($type, $weight) {
    $rates = include '../conf/constante.php';
    return floor($weight * $rates['POINTS_RATES'][$type]);
  }
}
?>