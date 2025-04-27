<?php
function getWasteTypeName($type) {
    $names = [
        'organico' => 'Orgánico',
        'inorganico' => 'Inorgánico',
        'reciclable' => 'Reciclable',
        'peligroso' => 'Peligroso'
    ];
    return $names[$type] ?? $type;
}

function getStatusName($status) {
    $names = [
        'pending' => 'Pendiente',
        'accepted' => 'Aceptada',
        'completed' => 'Completada',
        'rejected' => 'Rechazada'
    ];
    return $names[$status] ?? $status;
}

function calculatePoints($type, $weight) {
    $strategies = [
        'organico' => fn($w) => floor($w * 0.8),
        'inorganico' => fn($w) => floor($w * 0.5),
        'reciclable' => fn($w) => floor($w * 1),
        'peligroso' => fn($w) => floor($w * 2)
    ];
    
    return $strategies[$type] ? $strategies[$type]($weight) : 0;
}

function sendNotification($userId, $message) {
    global $pdo;
    
    // Obtener datos del usuario
    $stmt = $pdo->prepare("SELECT phone, whatsapp_notifications FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $user['whatsapp_notifications']) {
        // En un entorno real, aquí se integraría con la API de WhatsApp
        // Por ahora solo registramos en la base de datos
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, sent_at) 
                              VALUES (?, ?, NOW())");
        $stmt->execute([$userId, $message]);
        
        return true;
    }
    
    return false;
}
?>