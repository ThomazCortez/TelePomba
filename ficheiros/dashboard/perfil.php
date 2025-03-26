<?php
session_start(); // Inicia a sessão

// Verifica se o utilizador está logado
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado. Faça login primeiro.");
}

// Configurações da Base de Dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "telepomba";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Processar formulário quando submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter novo nome do formulário
    $novo_nome = trim($_POST['novo_nome']);

    // Validação básica
    if (empty($novo_nome)) {
        die("O nome não pode estar vazio.");
    }

    // Preparar e executar a query
    $stmt = $conn->prepare("UPDATE utilizadores SET nome_utilizador = ? WHERE id_utilizador = ?");
    $stmt->bind_param("si", $novo_nome, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $success = "Nome atualizado com sucesso para: " . htmlspecialchars($novo_nome);
    } else {
        $error = "Erro ao atualizar: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alterar Nome</title>
</head>
<body>
    <h2>Alterar Meu Nome</h2>

    <?php if (isset($success)) echo "<p style='color:green'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

    <form method="post">
        Novo Nome: 
        <input type="text" name="novo_nome" required>
        <input type="submit" value="Alterar">
    </form>
</body>
</html>