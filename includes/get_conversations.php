<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
redirectIfNotLoggedIn();

$userId = getCurrentUserId();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchTerm = "%$search%";

// Get conversations where the user is a participant
$sql = "
    SELECT 
        c.id_conversa, 
        c.nome, 
        c.tipo, 
        c.imagem_perfil,
        MAX(m.enviado_em) as last_message_time,
        u.nome_utilizador as other_user_name,
        u.imagem_perfil as other_user_image
    FROM conversas c
    JOIN participantes_conversa pc ON c.id_conversa = pc.id_conversa
    LEFT JOIN mensagens m ON c.id_conversa = m.id_conversa
    LEFT JOIN (
        SELECT pc2.id_conversa, u2.nome_utilizador, u2.imagem_perfil
        FROM participantes_conversa pc2
        JOIN utilizadores u2 ON pc2.id_utilizador = u2.id_utilizador
        WHERE pc2.id_utilizador != ?
    ) AS u ON c.id_conversa = u.id_conversa AND c.tipo = 'privada'
    WHERE pc.id_utilizador = ?
    AND (
        (c.tipo = 'grupo' AND c.nome LIKE ?) 
        OR 
        (c.tipo = 'privada' AND u.nome_utilizador LIKE ?)
    )
    GROUP BY c.id_conversa
    ORDER BY last_message_time DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId, $userId, $searchTerm, $searchTerm]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($conversations as &$conversation) {
    if ($conversation['tipo'] === 'privada') {
        // Use other user's details from the query
        $conversation['nome'] = $conversation['other_user_name'];
        $conversation['imagem_perfil'] = !empty($conversation['other_user_image']) 
            ? $conversation['other_user_image'] 
            : 'ficheiros/media/profiles/default_profile_image.jpg';
    } else {
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