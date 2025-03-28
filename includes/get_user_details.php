<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode(['error' => 'User ID not provided']);
    exit;
}

$userId = $_GET['user_id'];

try {
    $stmt = $pdo->prepare("SELECT nome_utilizador, imagem_perfil FROM utilizadores WHERE id_utilizador = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?>