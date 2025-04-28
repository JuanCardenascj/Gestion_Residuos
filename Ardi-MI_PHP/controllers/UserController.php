<?php
require_once '../models/Requests.php';
require_once '../models/Notification.php';

class UserController {
  private $requestModel;
  private $notificationModel;

  public function __construct() {
    $this->requestModel = new Requests();
    $this->notificationModel = new Notification();
  }

  public function dashboard() {
    $userId = $_SESSION['user']['id'];
    $requests = $this->requestModel->getUserRequests($userId);
    $points = $this->requestModel->getUserPoints($userId);
    
    require_once '../views/user/dashboard.php';
  }

  public function createRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'user_id' => $_SESSION['user']['id'],
        'date' => $_POST['collection_date'],
        'type' => $_POST['waste_type'],
        'weight' => $_POST['waste_weight'],
        'points' => $this->calculatePoints($_POST['waste_type'], $_POST['waste_weight'])
      ];
      
      if ($this->requestModel->create($data)) {
        $this->notificationModel->sendNewRequestNotification($data);
        
        $_SESSION['success'] = 'Solicitud creada correctamente';
        header('Location: /user/dashboard');
        exit();
      } else {
        $_SESSION['error'] = 'Error al crear la solicitud';
      }
    }
    
    header('Location: /user/dashboard');
    exit();
  }

  private function calculatePoints($type, $weight) {
    $rates = include '../conf/constante.php';
    return floor($weight * $rates['POINTS_RATES'][$type]);
  }
}
?>