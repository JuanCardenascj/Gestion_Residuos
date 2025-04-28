<?php
require_once '../models/User.php';
require_once '../models/Requests.php';
require_once '../models/Vehicle.php';

class AdminController {
  private $userModel;
  private $requestModel;
  private $vehicleModel;

  public function __construct() {
    $this->userModel = new User();
    $this->requestModel = new Requests();
    $this->vehicleModel = new Vehicle();
  }

  public function dashboard() {
    $users = $this->userModel->getAllUsers();
    $companies = $this->userModel->getAllCompanies();
    $requests = $this->requestModel->getAllRequests();
    $vehicles = $this->vehicleModel->getAllVehicles();
    
    require_once '../views/admin/dashboard.php';
  }

  public function filterRequests() {
    $filters = [
      'start_date' => $_POST['start_date'] ?? null,
      'end_date' => $_POST['end_date'] ?? null,
      'type' => $_POST['type'] ?? 'all',
      'min_weight' => $_POST['min_weight'] ?? 0,
      'max_weight' => $_POST['max_weight'] ?? 9999
    ];
    
    $requests = $this->requestModel->getFilteredRequests($filters);
    echo json_encode($requests);
  }

  public function exportUsersPDF() {
    // Lógica para generar PDF de usuarios
  }

  public function exportRequestsExcel() {
    // Lógica para generar Excel de solicitudes
  }
}
?>