<?php
require_once 'auth.php';
require_once '../config/database.php';

// Iniciar buffer de saída para prevenir qualquer eco acidental
ob_start();

try {
    redirectIfNotLoggedIn();
    
    // Garantir que só respondemos com JSON
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido');
    }

    // Ler o input JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data || !isset($data['conversationId'])) {
        throw new Exception('Dados inválidos');
    }

    $conversationId = $data['conversationId'];
    $userId = getCurrentUserId();

    // Validar IDs
    if (!is_numeric($conversationId) || !is_numeric($userId)) {
        throw new Exception('IDs inválidos');
    }

    // Remover usuário da conversa
    $stmt = $pdo->prepare("DELETE FROM participantes_conversa 
                          WHERE id_conversa = ? AND id_utilizador = ?");
    $stmt->execute([$conversationId, $userId]);

    // Get user's username
$stmt = $pdo->prepare("SELECT nome_utilizador FROM utilizadores WHERE id_utilizador = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $user['nome_utilizador'];

// Insert system message
$systemMessage = $username . " saiu da conversa.";
$stmt = $pdo->prepare("
    INSERT INTO mensagens (id_conversa, id_remetente, conteudo, tipo_mensagem, enviado_em)
    VALUES (?, NULL, ?, 'system', NOW())
");
$stmt->execute([$conversationId, $systemMessage]);

    // Limpar qualquer saída potencial antes de enviar JSON
    ob_end_clean();
    
    echo json_encode([
        'success' => true,
        'message' => 'Saiu da conversa com sucesso'
    ]);
    exit();

} catch (Exception $e) {
    // Limpar buffer e retornar erro
    ob_end_clean();
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit();
}