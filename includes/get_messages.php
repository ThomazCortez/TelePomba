<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
redirectIfNotLoggedIn();

if (!isset($_GET['conversation_id'])) {
    die('Conversation ID not provided');
}

$conversationId = $_GET['conversation_id'];
$userId = getCurrentUserId();

// Verify user is part of the conversation
$stmt = $pdo->prepare("
    SELECT 1 FROM participantes_conversa 
    WHERE id_conversa = ? AND id_utilizador = ?
");
$stmt->execute([$conversationId, $userId]);
if (!$stmt->fetch()) {
    die('You are not part of this conversation');
}

// Get conversation info
$stmt = $pdo->prepare("
    SELECT c.tipo, c.nome 
    FROM conversas c 
    WHERE c.id_conversa = ?
");
$stmt->execute([$conversationId]);
$conversation = $stmt->fetch(PDO::FETCH_ASSOC);

// For private chats, get the other participant's info
if ($conversation['tipo'] === 'privada') {
    $stmt = $pdo->prepare("
        SELECT u.nome_utilizador, u.imagem_perfil
        FROM utilizadores u
        JOIN participantes_conversa pc ON u.id_utilizador = pc.id_utilizador
        WHERE pc.id_conversa = ? AND pc.id_utilizador != ?
    ");
    $stmt->execute([$conversationId, $userId]);
    $otherUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $conversation['nome'] = $otherUser['nome_utilizador'];
    $conversation['imagem_perfil'] = $otherUser['imagem_perfil'];
}

// Update chat header
$image = $conversation['imagem_perfil'] ?? '';
echo '<div id="chatHeaderData" 
    data-conversation-id="' . $conversationId . '" 
    data-conversation-name="' . htmlspecialchars($conversation['nome']) . '"
    data-conversation-image="' . htmlspecialchars($image) . '"
    style="display:none;"></div>';

// Get messages with LEFT JOIN to include system messages
$stmt = $pdo->prepare("
    SELECT m.*, u.nome_utilizador, u.imagem_perfil
    FROM mensagens m
    LEFT JOIN utilizadores u ON m.id_remetente = u.id_utilizador
    WHERE m.id_conversa = ?
    ORDER BY m.enviado_em ASC
");
$stmt->execute([$conversationId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $message) {
    if (is_null($message['id_remetente'])) {
        // System message
        echo '<div class="text-center text-muted mb-3 system-message">';
        echo htmlspecialchars($message['conteudo']);
        echo '</div>';
        continue;
    }

    $isCurrentUser = $message['id_remetente'] == $userId;
    echo '<div class="mb-3 ' . ($isCurrentUser ? 'text-end' : 'text-start') . '">';
    echo '<div class="d-flex ' . ($isCurrentUser ? 'justify-content-end' : 'justify-content-start') . '">';
    
    if (!$isCurrentUser) {
        echo '<img src="' . htmlspecialchars($message['imagem_perfil']) . '" alt="' . htmlspecialchars($message['nome_utilizador']) . '" class="profile-img me-2">';
    }
    
    echo '<div>';
    if (!$isCurrentUser) {
        echo '<div class="fw-bold">' . htmlspecialchars($message['nome_utilizador']) . '</div>';
    }
    
    $messageType = $message['tipo_mensagem'] ?? 'text';
    switch ($messageType) {
        case 'image':
            $content = '<img src="' . htmlspecialchars($message['conteudo']) . '" class="img-fluid" style="max-width: 300px; border-radius: 10px;">';
            break;
        case 'video':
            $content = '<video controls style="max-width: 300px; border-radius: 10px;"><source src="' . htmlspecialchars($message['conteudo']) . '"></video>';
            break;
        case 'audio':
            $content = '<audio controls><source src="' . htmlspecialchars($message['conteudo']) . '"></audio>';
            break;
        default:
            $content = htmlspecialchars($message['conteudo']);
    }

    echo '<div class="p-2 rounded ' . ($isCurrentUser ? 'user-message' : 'other-message') . '">';
    echo $content;
    echo '</div>';
    echo '<small class="text-muted">' . date('H:i', strtotime($message['enviado_em'])) . '</small>';
    echo '</div>';
    
    if ($isCurrentUser) {
        echo '<img src="' . htmlspecialchars($_SESSION['imagem_perfil']) . '" alt="You" class="profile-img ms-2">';
    }
    
    echo '</div>'; // Close d-flex
    echo '</div>'; // Close mb-3
}
?>