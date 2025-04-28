<?php
require_once '../models/Requests.php';
require_once '../models/Vehicle.php';
require_once '../models/Notification.php';

class CompanyController {
  private $requestModel;
  private $vehicleModel;
  private $notificationModel;

  public function __construct() {
    $this->requestModel = new Requests();
    $this->vehicleModel = new Vehicle();
    $this->notificationModel = new Notification();
  }

  public function dashboard() {
    $companyId = $_SESSION['user']['id'];
    $pendingRequests = $this->requestModel->getPendingRequests();
    $acceptedRequests = $this->requestModel->getCompanyRequests($companyId, 'accepted');
    $completedRequests = $this->requestModel->getCompanyRequests($companyId, 'completed');
    $vehicles = $this->vehicleModel->getCompanyVehicles($companyId);
    
    require_once '../views/company/dashboard.php';
  }

  public function acceptRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $requestId = $_POST['request_id'];
      $vehicleId = $_POST['vehicle_id'];
      
      if ($this->requestModel->acceptRequest($requestId, $_SESSION['user']['id'], $vehicleId)) {
        $request = $this->requestModel->getRequestById($requestId);
        $this->notificationModel->sendRequestAcceptedNotification($request['user_id'], $requestId);
        
        $_SESSION['success'] = 'Solicitud aceptada correctamente';
      } else {
        $_SESSION['error'] = 'Error al aceptar la solicitud';
      }
      
      header('Location: /company/dashboard');
      exit();
    }
  }

  public function registerCollection() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $requestId = $_POST['request_id'];
      $weight = $_POST['collected_weight'];
      
      if ($this->requestModel->completeRequest($requestId, $weight)) {
        $request = $this->requestModel->getRequestById($requestId);
        $this->notificationModel->sendCollectionCompletedNotification($request['user_id'], $requestId);
        
        $_SESSION['success'] = 'Recolección registrada correctamente';
      } else {
        $_SESSION['error'] = 'Error al registrar la recolección';
      }
      
      header('Location: /company/dashboard');
      exit();
    }
  }
}
?>