<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
redirectIfNotLoggedIn();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$userId = getCurrentUserId();

try {
    $pdo->beginTransaction();
    
    // Create conversation
    $stmt = $pdo->prepare("
        INSERT INTO conversas (tipo, nome, criado_por)
        VALUES (?, ?, ?)
    ");
    
    $conversationType = $data['type'] === 'group' ? 'grupo' : 'privada';
    $conversationName = $conversationType === 'grupo' ? $data['groupName'] : null;
    
    $stmt->execute([$conversationType, $conversationName, $userId]);
    $conversationId = $pdo->lastInsertId();
    
    // Add creator as participant
    $stmt = $pdo->prepare("
        INSERT INTO participantes_conversa (id_conversa, id_utilizador)
        VALUES (?, ?)
    ");
    $stmt->execute([$conversationId, $userId]);
    
    // Add other participants
    foreach ($data['participants'] as $participantId) {
        $stmt->execute([$conversationId, $participantId]);
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'conversationId' => $conversationId
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'Error creating conversation: ' . $e->getMessage()
    ]);
}
?>