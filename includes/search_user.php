<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
redirectIfNotLoggedIn();

if (!isset($_GET['username'])) {
    die('Username not provided');
}

$username = $_GET['username'];
$currentUserId = getCurrentUserId();

$stmt = $pdo->prepare("
    SELECT id_utilizador, nome_utilizador, imagem_perfil
    FROM utilizadores
    WHERE nome_utilizador LIKE ? AND id_utilizador != ?
    LIMIT 1
");
$stmt->execute(["%$username%", $currentUserId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($user ?: null);
?>