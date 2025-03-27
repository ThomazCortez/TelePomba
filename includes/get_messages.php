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
echo '<script>';
echo 'document.getElementById("chatHeader").innerHTML = `';
echo '<div class="d-flex align-items-center">';
echo '<img src="'.$conversation['imagem_perfil'].'" alt="'.$conversation['nome'].'" class="profile-img me-2">';
echo '<h5 class="m-0">'.$conversation['nome'].'</h5>';
echo '</div>';
echo 'document.getElementById("chatHeader").dataset.conversationId = "'.$conversationId.'";';
echo '</script>';

// Get messages
$stmt = $pdo->prepare("
    SELECT m.*, u.nome_utilizador, u.imagem_perfil
    FROM mensagens m
    JOIN utilizadores u ON m.id_remetente = u.id_utilizador
    WHERE m.id_conversa = ?
    ORDER BY m.enviado_em ASC
");
$stmt->execute([$conversationId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $message) {
    $isCurrentUser = $message['id_remetente'] == $userId;
    echo '<div class="mb-3 '.($isCurrentUser ? 'text-end' : 'text-start').'">';
    echo '<div class="d-flex '.($isCurrentUser ? 'justify-content-end' : 'justify-content-start').'">';
    if (!$isCurrentUser) {
        echo '<img src="'.$message['imagem_perfil'].'" alt="'.$message['nome_utilizador'].'" class="profile-img me-2">';
    }
    echo '<div>';
    if (!$isCurrentUser) {
        echo '<div class="fw-bold">'.$message['nome_utilizador'].'</div>';
    }
    echo '<div class="p-2 rounded '.($isCurrentUser ? 'bg-primary text-white' : 'bg-white').'">';
    echo $message['conteudo'];
    echo '</div>';
    echo '<small class="text-muted">'.date('H:i', strtotime($message['enviado_em'])).'</small>';
    echo '</div>';
    if ($isCurrentUser) {
        echo '<img src="'.$_SESSION['profile_image'].'" alt="You" class="profile-img ms-2">';
    }
    echo '</div>';
}
?>