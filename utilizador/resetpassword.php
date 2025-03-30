<?php
// Configurações de segurança
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Security-Policy: default-src \'self\'; script-src \'self\'; style-src \'self\'');

// Iniciar sessão segura
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();

// Configurações de ambiente
require_once 'config.php';

// Função para limpar e validar entrada
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

// Função para validar força da senha
function checkPasswordStrength($password) {
    $length = strlen($password);
    $hasUppercase = preg_match('/[A-Z]/', $password);
    $hasLowercase = preg_match('/[a-z]/', $password);
    $hasNumber = preg_match('/[0-9]/', $password);
    $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);

    $strength = 0;
    if ($length >= 8) $strength++;
    if ($hasUppercase) $strength++;
    if ($hasLowercase) $strength++;
    if ($hasNumber) $strength++;
    if ($hasSpecialChar) $strength++;

    return $strength;
}

// Variáveis para armazenar mensagens
$error = '';
$success = '';
$debug_info = '';
$token_valid = false;
$reset_token = '';
$show_try_again = false;

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Verificar token na URL
    $reset_token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $_SESSION['reset_token'] = $reset_token;
    
    if (!$reset_token) {
        $error = "Token de redefinição de palavra-passe inválido.";
    } else {
        try {
            // Conexão com o banco de dados usando PDO
            $pdo = new PDO(
                "mysql:host={$DB_CONFIG['host']};dbname={$DB_CONFIG['database']};charset=utf8mb4", 
                $DB_CONFIG['username'], 
                $DB_CONFIG['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            // Verificar token
            $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
            $stmt->execute(['token' => hash('sha256', $reset_token)]);
            $reset_request = $stmt->fetch();

            if ($reset_request) {
                $token_valid = true;
                $_SESSION['reset_email'] = $reset_request['email'];
            } else {
                $error = "Link de redefinição de palavra-passe expirado ou inválido.";
            }
        } catch (PDOException $e) {
            error_log("Erro de banco de dados: " . $e->getMessage());
            $error = "Erro interno do sistema. Por favor, tente novamente mais tarde.";
            $debug_info = "Detalhes técnicos: " . $e->getMessage();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proteção contra CSRF
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || 
        $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
        $error = "Erro de segurança. Por favor, tente novamente.";
        $show_try_again = true;
    } else {
        // Validar nova senha
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $reset_token = $_SESSION['reset_token'] ?? '';

        if (empty($new_password) || empty($confirm_password)) {
            $error = "Por favor, preencha todos os campos.";
            $show_try_again = true;
        } elseif ($new_password !== $confirm_password) {
            $error = "As palavras-passe não coincidem.";
            $show_try_again = true;
        } else {
            $password_strength = checkPasswordStrength($new_password);
            if ($password_strength < 3) {
                $error = "A palavra-passe é fraca. Use pelo menos 8 caracteres, incluindo maiúsculas, minúsculas, números e caracteres especiais.";
                $show_try_again = true;
            } else {
                try {
                    $pdo = new PDO(
                        "mysql:host={$DB_CONFIG['host']};dbname={$DB_CONFIG['database']};charset=utf8mb4", 
                        $DB_CONFIG['username'], 
                        $DB_CONFIG['password'],
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false,
                        ]
                    );

                    if (!isset($_SESSION['reset_email'])) {
                        $error = "Sessão inválida. Por favor, solicite uma nova redefinição de palavra-passe.";
                        $show_try_again = true;
                        goto end_processing;
                    }

                    $hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);

                    $stmt = $pdo->prepare("UPDATE utilizadores SET palavra_passe = :password WHERE email = :email");
                    $stmt->execute([
                        'password' => $hashed_password,
                        'email' => $_SESSION['reset_email']
                    ]);

                    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = :email");
                    $stmt->execute(['email' => $_SESSION['reset_email']]);

                    unset($_SESSION['reset_email']);
                    unset($_SESSION['reset_token']);
                    session_regenerate_id(true);

                    $success = "Palavra-passe redefinida com sucesso. Você pode agora fazer login.";
                } catch (PDOException $e) {
                    error_log("Erro de banco de dados: " . $e->getMessage());
                    $error = "Erro interno do sistema. Por favor, tente novamente mais tarde.";
                    $debug_info = "Detalhes técnicos: " . $e->getMessage();
                    $show_try_again = true;
                }
            }
        }
    }
}

end_processing:

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Palavra-passe - TelePomba</title>
    <link rel="stylesheet" href="css/resetpassword.css">
</head>
<body>
    <div class="reset-password-container">
        <div class="reset-password-header">
            <h1>Redefinir Palavra-passe</h1>
        </div>
        
        <div class="reset-password-content">
            <?php if (!empty($error)): ?>
                <div class="message error-message">
                    <?php echo $error; ?>
                </div>
                <?php if ($show_try_again && !empty($reset_token)): ?>
                    <div class="try-again-container">
                        <a href="resetpassword.php?token=<?php echo $reset_token; ?>" class="try-again-link">Tente Novamente</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="message success-message">
                    <?php echo $success; ?>
                    <div class="back-to-login">
                        <a href="login.php">Voltar para o login</a>
                    </div>
                </div>
            <?php elseif ($token_valid): ?>
                <p>Digite a sua nova palavra-passe. Certifique-se de usar uma palavra-passe forte.</p>
                
                <form method="post" id="resetPasswordForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="form-group">
                        <label for="new_password">Nova Palavra-passe:</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                        <div class="password-strength">
                            <span id="password-strength-text"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Palavra-passe:</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn-submit">Redefinir Palavra-passe</button>
                </form>
            <?php else: ?>
                <div class="back-to-login">
                    <a href="login.php">Voltar para o login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('new_password');
        const strengthMeter = document.querySelector('.password-strength-meter-fill');
        const strengthText = document.getElementById('password-strength-text');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let strengthClass = '';
            let strengthMessage = '';

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;

            strengthMeter.style.width = `${strength * 20}%`;
            
            if (strength <= 1) {
                strengthClass = 'weak';
                strengthMessage = 'Muito Fraca';
            } else if (strength <= 3) {
                strengthClass = 'medium';
                strengthMessage = 'Média';
            } else {
                strengthClass = 'strong';
                strengthMessage = 'Forte';
            }

            strengthMeter.className = 'password-strength-meter-fill ' + strengthClass;
            strengthText.textContent = strengthMessage;
        });
    });
    </script>
</body>
</html>