<?php
// Configurações da Base de Dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "telepomba";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Processar formulário quando submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter valores do formulário
    $id_utilizador = $_POST['id_utilizador'];
    $novo_nome = $_POST['novo_nome'];

    // Preparar e executar a query com prepared statement
    $stmt = $conn->prepare("UPDATE utilizadores SET nome_utilizador = ? WHERE id_utilizador = ?");
    $stmt->bind_param("si", $novo_nome, $id_utilizador);

    if ($stmt->execute()) {
        echo "Nome de utilizador atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<body>
<h2>Alterar Nome de Utilizador</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    ID do Utilizador: <input type="number" name="id_utilizador" required><br><br>
    Novo Nome: <input type="text" name="novo_nome" required><br><br>
    <input type="submit" value="Alterar">
</form>
</body>
</html>