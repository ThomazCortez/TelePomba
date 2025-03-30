<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
redirectIfNotLoggedIn();

header('Content-Type: application/json');

$userId = getCurrentUserId();

try {
    $pdo->beginTransaction();
    
    // Processar dados do formulário
    $conversationType = ($_POST['type'] === 'group') ? 'grupo' : 'privada';
    $groupName = $conversationType === 'grupo' ? $_POST['groupName'] : null;
    $participants = json_decode($_POST['participants']);
    
    // Processar upload da imagem
    $groupImage = 'ficheiros/media/groups/default_group_image.jpg';
    if ($conversationType === 'grupo' && !empty($_FILES['groupPhoto'])) {
        $uploadDir = '../ficheiros/media/groups/';
        $fileName = uniqid() . '_' . basename($_FILES['groupPhoto']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['groupPhoto']['tmp_name'], $targetPath)) {
            $groupImage = 'ficheiros/media/groups/' . $fileName;
        }
    }

    // Criar conversa
    $stmt = $pdo->prepare("
        INSERT INTO conversas (tipo, nome, criado_por, imagem_perfil)
        VALUES (?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $conversationType,
        $groupName,
        $userId,
        $groupImage
    ]);
    
    $conversationId = $pdo->lastInsertId();
    
    // Adicionar participantes
    $stmt = $pdo->prepare("
        INSERT INTO participantes_conversa (id_conversa, id_utilizador)
        VALUES (?, ?)
    ");
    
    // Adicionar criador
    $stmt->execute([$conversationId, $userId]);
    
    // Adicionar outros participantes
    foreach ($participants as $participantId) {
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
        'message' => 'Erro ao criar conversa: ' . $e->getMessage()
    ]);
}