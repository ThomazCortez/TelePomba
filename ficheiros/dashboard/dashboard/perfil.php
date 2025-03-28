<?php

session_start();

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "telepomba";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$id_utilizador = $_SESSION['user_id'];

// Busca os dados atuais do usuário
$stmt = $conn->prepare("SELECT nome_utilizador, email, descricao, estado, imagem_perfil FROM utilizadores WHERE id_utilizador = ?");
$stmt->bind_param("i", $id_utilizador);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Inicializa os valores para exibição no formulário
$nome_utilizador = $user['nome_utilizador'] ?? '';
$email = $user['email'] ?? '';
$descricao = $user['descricao'] ?? '';
$estado = $user['estado'] ?? 'offline';
$imagem_perfil = $user['imagem_perfil'] ?? '';

// Variáveis para feedback
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém e valida os dados dos campos
    $nome_utilizador = isset($_POST['nome_utilizador']) ? trim($_POST['nome_utilizador']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
    $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';

    // Campos para nova senha
    $nova_palavra_passe = isset($_POST['nova_palavra_passe']) ? trim($_POST['nova_palavra_passe']) : '';
    $confirmar_palavra_passe = isset($_POST['confirmar_palavra_passe']) ? trim($_POST['confirmar_palavra_passe']) : '';

    if (empty($nome_utilizador)) {
        $error = "Nome de utilizador não pode estar vazio!";
    } elseif (empty($email)) {
        $error = "Email não pode estar vazio!";
    } elseif (!empty($nova_palavra_passe) && ($nova_palavra_passe !== $confirmar_palavra_passe)) {
        $error = "As senhas não coincidem!";
    } else {
        // Inicia a construção da query para atualizar os dados
        $updateQuery = "UPDATE utilizadores SET nome_utilizador = ?, email = ?, descricao = ?, estado = ?";
        $params = [$nome_utilizador, $email, $descricao, $estado];
        $param_types = "ssss";

        // Se uma nova senha foi informada, faz o hash e adiciona na query
        if (!empty($nova_palavra_passe)) {
            $hashed_password = password_hash($nova_palavra_passe, PASSWORD_DEFAULT);
            $updateQuery .= ", palavra_passe = ?";
            $params[] = $hashed_password;
            $param_types .= "s";
        }

        // Se uma imagem de perfil foi enviada, trata o upload
        if (isset($_FILES['imagem_perfil']) && $_FILES['imagem_perfil']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $filename = basename($_FILES['imagem_perfil']['name']);
            $targetFile = $targetDir . time() . "_" . $filename;
            if (move_uploaded_file($_FILES['imagem_perfil']['tmp_name'], $targetFile)) {
                // Aqui você atualiza a query para salvar o caminho no banco
                $updateQuery .= ", imagem_perfil = ?";
                $params[] = $targetFile;
                $param_types .= "s";
                // Atualize também a variável de sessão:
                $_SESSION['imagem_perfil'] = $targetFile;
            } else {
                $error = "Erro no upload da imagem de perfil.";
            }
        }

        // Completa a query com a condição do id
        $updateQuery .= " WHERE id_utilizador = ?";
        $params[] = $id_utilizador;
        $param_types .= "i";

        // Se não houve erro no upload, prossegue com a atualização
        if (empty($error)) {
            $stmt = $conn->prepare($updateQuery);
            if ($stmt === false) {
                die("Erro na preparação da query: " . $conn->error);
            }

            // Faz o bind dinâmico dos parâmetros
            $bind_names[] = $param_types;
            for ($i = 0; $i < count($params); $i++) {
                $bind_names[] = &$params[$i];
            }
            call_user_func_array([$stmt, 'bind_param'], $bind_names);

            if ($stmt->execute()) {
                $success = "Dados atualizados com sucesso!";
                // Atualiza a variável de imagem se foi alterada
                if (!empty($targetFile)) {
                    $imagem_perfil = $targetFile;
                }
            } else {
                $error = "Erro ao atualizar os dados: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - TelePomba</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <style>
        :root {
            --primary-color: #2a6b5f;
            --secondary-color: #f5f5f5;
            --accent-color: #ff6b6b;
            --text-color: #333;
            --light-text: #fff;
            --light-gray-bg: #f8f9fa;
        }

        body {
            background-color: var(--light-gray-bg);
        }

        .settings-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .profile-img {
            display: block;
            margin: 0 auto 15px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="settings-container">
        <div class="position-relative mb-4">
            <!-- Seta à esquerda -->
            <a href="../../../home.php" class="position-absolute top-0 start-0 text-dark p-2" title="Voltar">
            <i class="fas fa-arrow-left fa-lg"></i>
            </a>
            <h2 class="text-center">Configurações de Conta</h2>
        </div>
            <?php if (!empty($imagem_perfil)) : ?>
                <img src="<?php echo htmlspecialchars($imagem_perfil); ?>" alt="Imagem de Perfil" class="profile-img">
            <?php endif; ?>
            <form method="post" action="perfil.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nome_utilizador" class="form-label">Nome de Utilizador</label>
                    <input type="text" id="nome_utilizador" name="nome_utilizador" class="form-control" value="<?php echo htmlspecialchars($nome_utilizador); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-control"><?php echo htmlspecialchars($descricao); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-select">
                        <option value="online" <?php echo ($estado === 'online') ? 'selected' : ''; ?>>Online</option>
                        <option value="ausente" <?php echo ($estado === 'ausente') ? 'selected' : ''; ?>>Ausente</option>
                        <option value="ocupado" <?php echo ($estado === 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
                        <option value="offline" <?php echo ($estado === 'offline') ? 'selected' : ''; ?>>Offline</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="imagem_perfil" class="form-label">Imagem de Perfil</label>
                    <input type="file" id="imagem_perfil" name="imagem_perfil" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="nova_palavra_passe" class="form-label">Nova Senha</label>
                    <input type="password" id="nova_palavra_passe" name="nova_palavra_passe" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="confirmar_palavra_passe" class="form-label">Confirmar Nova Senha</label>
                    <input type="password" id="confirmar_palavra_passe" name="confirmar_palavra_passe" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                <a href="../../../home.php" class="btn btn-outline-danger w-100 mt-2">
                <i class="fas fa-sign-out-alt me-2"></i>Sair
                </a>
            </form>     
        </div>
    </div>
</body>
</html>