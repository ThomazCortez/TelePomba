<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Configuração da Base de Dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "telepomba";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Variáveis para mensagens
$error = [];
$success = [];

// Processar alteração de password
if (isset($_POST['alterar_password'])) {
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error['password'] = "Todos os campos são obrigatórios!";
    } elseif ($new_password !== $confirm_password) {
        $error['password'] = "As novas passwords não coincidem!";
    } elseif (strlen($new_password) < 8) {
        $error['password'] = "A nova password deve ter pelo menos 8 caracteres!";
    } else {
        $stmt = $conn->prepare("SELECT palavra_passe FROM utilizadores WHERE id_utilizador = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            
            if (password_verify($current_password, $hashed_password)) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                $update_stmt = $conn->prepare("UPDATE utilizadores SET palavra_passe = ? WHERE id_utilizador = ?");
                $update_stmt->bind_param("si", $new_hashed_password, $_SESSION['user_id']);
                
                if ($update_stmt->execute()) {
                    $success['password'] = "Password alterada com sucesso!";
                } else {
                    $error['password'] = "Erro ao atualizar: " . $conn->error;
                }
                $update_stmt->close();
            } else {
                $error['password'] = "Password atual incorreta!";
            }
        } else {
            $error['password'] = "Utilizador não encontrado!";
        }
        $stmt->close();
    }
}

// Processar alteração de nome
if (isset($_POST['alterar_nome'])) {
    $novo_nome = trim($_POST['novo_nome'] ?? '');

    if (empty($novo_nome)) {
        $error['nome'] = "O nome não pode estar vazio!";
    } elseif (strlen($novo_nome) < 3) {
        $error['nome'] = "O nome deve ter pelo menos 3 caracteres!";
    } else {
        $stmt = $conn->prepare("UPDATE utilizadores SET nome_utilizador = ? WHERE id_utilizador = ?");
        $stmt->bind_param("si", $novo_nome, $_SESSION['user_id']);

        if ($stmt->execute()) {
            $success['nome'] = "Nome atualizado com sucesso!";
        } else {
            $error['nome'] = "Erro ao atualizar: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Processar alteração de email
if (isset($_POST['alterar_email'])) {
    $novo_email = trim($_POST['novo_email'] ?? '');
    $confirmar_email = trim($_POST['confirmar_email'] ?? '');
    $current_password_email = trim($_POST['current_password_email'] ?? '');

    if (empty($novo_email) || empty($confirmar_email) || empty($current_password_email)) {
        $error['email'] = "Todos os campos são obrigatórios!";
    } elseif ($novo_email !== $confirmar_email) {
        $error['email'] = "Os e-mails não coincidem!";
    } elseif (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Formato de e-mail inválido!";
    } else {
        $stmt = $conn->prepare("SELECT palavra_passe, email FROM utilizadores WHERE id_utilizador = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($hashed_password, $email_atual);
            $stmt->fetch();
            
            if (password_verify($current_password_email, $hashed_password)) {
                $check_stmt = $conn->prepare("SELECT id_utilizador FROM utilizadores WHERE email = ?");
                $check_stmt->bind_param("s", $novo_email);
                $check_stmt->execute();
                $check_stmt->store_result();
                
                if ($check_stmt->num_rows > 0) {
                    $error['email'] = "Este e-mail já está em uso!";
                } else {
                    $update_stmt = $conn->prepare("UPDATE utilizadores SET email = ? WHERE id_utilizador = ?");
                    $update_stmt->bind_param("si", $novo_email, $_SESSION['user_id']);
                    
                    if ($update_stmt->execute()) {
                        $success['email'] = "E-mail atualizado com sucesso!";
                    } else {
                        $error['email'] = "Erro ao atualizar: " . $conn->error;
                    }
                    $update_stmt->close();
                }
                $check_stmt->close();
            } else {
                $error['email'] = "Password atual incorreta!";
            }
        } else {
            $error['email'] = "Utilizador não encontrado!";
        }
        $stmt->close();
    }
}

// Processar alteração de descrição
if (isset($_POST['alterar_descricao'])) {
    $nova_descricao = trim($_POST['nova_descricao'] ?? '');

    // Validação ajustada
    if (strlen($nova_descricao) > 500) {
        $error['descricao'] = "A descrição não pode exceder 500 caracteres!";
    } else {
        // Verifique se a coluna existe na tabela
        $check_column = $conn->query("SHOW COLUMNS FROM utilizadores LIKE 'descricao'");
        if ($check_column->num_rows == 0) {
            // Se a coluna não existir, crie-a
            $conn->query("ALTER TABLE utilizadores ADD COLUMN descricao TEXT");
        }

        $stmt = $conn->prepare("UPDATE utilizadores SET descricao = ? WHERE id_utilizador = ?");
        if ($stmt === false) {
            $error['descricao'] = "Erro ao preparar a query: " . $conn->error;
        } else {
            $stmt->bind_param("si", $nova_descricao, $_SESSION['user_id']);

            if ($stmt->execute()) {
                $success['descricao'] = "Descrição atualizada com sucesso!";
            } else {
                $error['descricao'] = "Erro ao atualizar: " . $stmt->error;
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
    <title>Perfil - TelePomba</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .alert {
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            min-height: 100px;
            resize: vertical;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <h1>Gestão de Perfil</h1>

    <!-- Alterar Nome -->
    <div class="section">
        <h2>Alterar Nome</h2>
        <?php if (isset($success['nome'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success['nome']) ?></div>
        <?php endif; ?>
        <?php if (isset($error['nome'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error['nome']) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label>Novo Nome:</label>
                <input type="text" name="novo_nome" required minlength="3">
            </div>
            <button type="submit" name="alterar_nome">Alterar Nome</button>
        </form>
    </div>

    <!-- Alterar Email -->
    <div class="section">
        <h2>Alterar E-mail</h2>
        <?php if (isset($success['email'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success['email']) ?></div>
        <?php endif; ?>
        <?php if (isset($error['email'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error['email']) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label>Novo E-mail:</label>
                <input type="email" name="novo_email" required>
            </div>
            <div class="form-group">
                <label>Confirmar E-mail:</label>
                <input type="email" name="confirmar_email" required>
            </div>
            <div class="form-group">
                <label>Password Atual:</label>
                <input type="password" name="current_password_email" required>
            </div>
            <button type="submit" name="alterar_email">Alterar E-mail</button>
        </form>
    </div>

    <!-- Alterar Password -->
    <div class="section">
        <h2>Alterar Password</h2>
        <?php if (isset($success['password'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success['password']) ?></div>
        <?php endif; ?>
        <?php if (isset($error['password'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error['password']) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label>Password Atual:</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>Nova Password:</label>
                <input type="password" name="new_password" required minlength="8">
            </div>
            <div class="form-group">
                <label>Confirmar Password:</label>
                <input type="password" name="confirm_password" required minlength="8">
            </div>
            <button type="submit" name="alterar_password">Alterar Password</button>
        </form>
    </div>
<!-- Alterar Descrição -->
    <div class="section">
        <h2>Alterar Descrição</h2>
        <?php if (isset($success['descricao'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success['descricao']) ?></div>
        <?php endif; ?>
        <?php if (isset($error['descricao'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error['descricao']) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label>Nova Descrição:</label>
                <textarea name="nova_descricao" required minlength="3"></textarea>
            </div>
            <button type="submit" name="alterar_descricao">Alterar Descrição</button>
        </form>
    </div>
</body>
</html>