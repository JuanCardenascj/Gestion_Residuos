<?php
require_once '../conf/constante.php';

class Notification {
  private $db;

  public function __construct() {
    $this->db = Database::getInstance();
  }

  public function sendNewRequestNotification($requestData) {
    // Notificar a las empresas sobre nueva solicitud
    $message = "Nueva solicitud de recolección: " . 
               "Tipo: " . WASTE_TYPES[$requestData['type']] . ", " .
               "Peso: " . $requestData['weight'] . "kg, " .
               "Fecha: " . $requestData['date'];
    
    $this->sendToCompanies($message);
  }

  public function sendRequestAcceptedNotification($userId, $requestId) {
    // Notificar al usuario que su solicitud fue aceptada
    $message = "Tu solicitud #$requestId ha sido aceptada por una empresa recolectora";
    $this->sendToUser($userId, $message);
  }

  public function sendCollectionCompletedNotification($userId, $requestId) {
    // Notificar al usuario que su recolección fue completada
    $message = "Tu solicitud #$requestId ha sido completada. ¡Gracias por reciclar!";
    $this->sendToUser($userId, $message);
  }

  private function sendToCompanies($message) {
    // Enviar notificación a todas las empresas activas
    $stmt = $this->db->prepare("SELECT * FROM users WHERE role = 'company' AND status = 'active'");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($companies as $company) {
      if ($company['whatsapp']) {
        $this->sendWhatsApp($company['phone'], $message);
      }
    }
  }

  private function sendToUser($userId, $message) {
    // Enviar notificación a un usuario específico
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $user['whatsapp']) {
      $this->sendWhatsApp($user['phone'], $message);
    }
  }

  private function sendWhatsApp($phone, $message) {
    // Simulación de envío por WhatsApp (en producción usar API real)
    if (WHATSAPP_NOTIFICATIONS_ENABLED) {
      error_log("WhatsApp enviado a $phone: $message");
      return true;
    }
    return false;
  }
}
?>