<?php
class NotificationSystem {
    private $observers = [];
    
    public function subscribe($observer) {
        $this->observers[] = $observer;
    }
    
    public function unsubscribe($observer) {
        $this->observers = array_filter($this->observers, 
            fn($obs) => $obs !== $observer);
    }
    
    public function notify($data) {
        foreach ($this->observers as $observer) {
            $observer->update($data);
        }
    }
}

class WhatsAppNotifier {
    public function update($data) {
        global $pdo;
        
        // Registrar notificación en la base de datos
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, channel, sent_at) 
                              VALUES (?, ?, 'whatsapp', NOW())");
        $stmt->execute([$data['userId'], $data['message']]);
        
        // En producción, aquí se integraría con la API de WhatsApp
        error_log("Notificación por WhatsApp a {$data['phone']}: {$data['message']}");
    }
}

class EmailNotifier {
    public function update($data) {
        global $pdo;
        
        // Registrar notificación en la base de datos
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, channel, sent_at) 
                              VALUES (?, ?, 'email', NOW())");
        $stmt->execute([$data['userId'], $data['message']]);
        
        // En producción, aquí se enviaría el correo
        error_log("Notificación por email a {$data['email']}: {$data['message']}");
    }
}
?>