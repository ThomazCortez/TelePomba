<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
redirectIfNotLoggedIn();

$userId = getCurrentUserId();

// Get conversations where the user is a participant
$stmt = $pdo->prepare("
    SELECT 
        c.id_conversa, 
        c.nome, 
        c.tipo, 
        c.imagem_perfil,
        MAX(m.enviado_em) as last_message_time
    FROM conversas c
    JOIN participantes_conversa pc ON c.id_conversa = pc.id_conversa
    LEFT JOIN mensagens m ON c.id_conversa = m.id_conversa
    WHERE pc.id_utilizador = ?
    GROUP BY c.id_conversa
    ORDER BY last_message_time DESC
");
$stmt->execute([$userId]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($conversations as &$conversation) {
    // Get conversation details
    if ($conversation['tipo'] === 'privada') {
        // Private chat - get other participant's info
        $stmt = $pdo->prepare("
            SELECT 
                u.nome_utilizador, 
                u.imagem_perfil
            FROM utilizadores u
            JOIN participantes_conversa pc ON u.id_utilizador = pc.id_utilizador
            WHERE pc.id_conversa = ? AND pc.id_utilizador != ?
        ");
        $stmt->execute([$conversation['id_conversa'], $userId]);
        $otherUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($otherUser) {
            $conversation['nome'] = $otherUser['nome_utilizador'];
            $conversation['imagem_perfil'] = !empty($otherUser['imagem_perfil']) 
                ? $otherUser['imagem_perfil'] 
                : 'ficheiros/media/profiles/default_profile_image.jpg';
        }
    } else {
        // Group chat - use group image or default
        $conversation['imagem_perfil'] = !empty($conversation['imagem_perfil'])
            ? $conversation['imagem_perfil']
            : 'ficheiros/media/groups/default_group_image.jpg';
    }

    // Get last message
    $stmt = $pdo->prepare("
        SELECT 
            m.conteudo, 
            m.enviado_em, 
            u.nome_utilizador,
            m.tipo_mensagem
        FROM mensagens m
        JOIN utilizadores u ON m.id_remetente = u.id_utilizador
        WHERE m.id_conversa = ?
        ORDER BY m.enviado_em DESC
        LIMIT 1
    ");
    $stmt->execute([$conversation['id_conversa']]);
    $lastMessage = $stmt->fetch(PDO::FETCH_ASSOC);

    // Format last message content
    if ($lastMessage) {
        switch ($lastMessage['tipo_mensagem']) {
            case 'image':
                $content = '📷 Foto';
                break;
            case 'video':
                $content = '🎥 Vídeo';
                break;
            case 'audio':
                $content = '🎧 Áudio';
                break;
            default:
                $content = $lastMessage['conteudo'];
        }
    }

    // Output conversation item
    echo '<div class="p-3 border-bottom conversation-item" 
            data-conversation-id="'.$conversation['id_conversa'].'" 
            data-conversation-name="'.htmlspecialchars($conversation['nome']).'"
            data-conversation-type="'.$conversation['tipo'].'"
            style="cursor: pointer;">';
    
    echo '<div class="d-flex align-items-center">';
    echo '<img src="'.htmlspecialchars($conversation['imagem_perfil']).'" 
              alt="'.htmlspecialchars($conversation['nome']).'" 
              class="profile-img me-3">';
    echo '<div class="flex-grow-1">';
    echo '<div class="d-flex justify-content-between">';
    echo '<h6 class="mb-0">'.htmlspecialchars($conversation['nome']).'</h6>';
    if ($lastMessage) {
        echo '<small class="text-muted">'.date('H:i', strtotime($lastMessage['enviado_em'])).'</small>';
    }
    echo '</div>';
    if ($lastMessage) {
        echo '<small class="text-muted text-truncate d-block">';
        echo htmlspecialchars($lastMessage['nome_utilizador'].': '.$content);
        echo '</small>';
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>