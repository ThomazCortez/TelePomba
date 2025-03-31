<?php
// Configurações de segurança
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Security-Policy: default-src \'self\'; script-src \'self\' https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/; style-src \'self\' https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/ https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/ \'unsafe-inline\'; font-src \'self\' https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/ https://fonts.googleapis.com; img-src \'self\' data:;');

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
    $reset_token = urldecode(filter_input(INPUT_GET, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
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
            $pdo->exec("SET time_zone = '+00:00';");

            // Verificar token
            $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > UTC_TIMESTAMP()");
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
                    $pdo->exec("SET time_zone = '+00:00';");

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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/resetpassword.css">
</head>
<body>
    <div class="page-background"></div>
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="card reset-password-container border-0 shadow-lg">
            <div class="card-header text-white text-center py-4 border-0 rounded-top bg-primary">
                <h3 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Redefinir Palavra-passe</h3>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo nl2br(htmlspecialchars($error)); ?>
                        <?php if (!empty($debug_info)): ?>
                            <div class="mt-2 small text-muted"><?php echo nl2br(htmlspecialchars($debug_info)); ?></div>
                        <?php endif; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php if ($show_try_again && !empty($reset_token)): ?>
                        <div class="text-center mb-4">
                            <a href="resetpassword.php?token=<?php echo urlencode($reset_token); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Tentar Novamente
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="text-center my-4">
                        <div class="mt-4">
                            <a href="login.php" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Ir para o Login
                            </a>
                        </div>
                    </div>
                <?php elseif ($token_valid): ?>
                    <div class="text-center mb-4">
                        <i class="bi bi-key text-primary" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Crie uma nova palavra-passe segura para a sua conta.</p>
                    </div>
                    
                    <form method="post" id="resetPasswordForm" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-floating mb-4">
                            <input type="password" id="new_password" name="new_password" class="form-control" 
                                placeholder="Nova palavra-passe" required minlength="8">
                            <label for="new_password"><i class="bi bi-lock-fill me-2"></i>Nova Palavra-passe</label>
                            <div class="invalid-feedback">
                                Introduza uma palavra-passe com pelo menos 8 caracteres.
                            </div>
                            <div class="password-strength mt-2">
                                <div class="password-strength-meter-fill"></div>
                            </div>
                            <small id="password-strength-text" class="form-text text-muted"></small>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                                placeholder="Confirmar palavra-passe" required>
                            <label for="confirm_password"><i class="bi bi-lock-check-fill me-2"></i>Confirmar Palavra-passe</label>
                            <div class="invalid-feedback">
                                As palavras-passe devem coincidir.
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                        <button type="submit" class="btn-submit btn-lg">
                            <i class="bi bi-check2-circle me-2"></i>Redefinir Palavra-passe
                        </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="text-center my-4">
                        <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">O link de redefinição de palavra-passe é inválido ou expirou.</p>
                        <div class="mt-4">
                            <a href="forgotpassword.php" class="btn btn-outline-primary me-2">
                                <i class="bi bi-envelope me-2"></i>Solicitar Novo Link
                            </a>
                            <a href="login.php" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Voltar para o Login
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-footer bg-transparent text-center py-3 text-muted">
                <small>&copy; <?php echo date('Y'); ?> TelePomba. Todos os direitos reservados.</small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Password strength checker and form validation script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password strength checker
        const passwordInput = document.getElementById('new_password');
        const confirmInput = document.getElementById('confirm_password');
        const strengthMeter = document.querySelector('.password-strength-meter-fill');
        const strengthText = document.getElementById('password-strength-text');
        
        if (passwordInput) {
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

                // Remove previous classes
                strengthMeter.classList.remove('weak', 'medium', 'strong');
                // Add new class
                strengthMeter.classList.add(strengthClass);
                strengthText.textContent = strengthMessage;
            });
        }
        
        // Check password match
        if (confirmInput && passwordInput) {
            confirmInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('As palavras-passe não coincidem');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            passwordInput.addEventListener('input', function() {
                if (confirmInput.value !== '' && confirmInput.value !== this.value) {
                    confirmInput.setCustomValidity('As palavras-passe não coincidem');
                } else {
                    confirmInput.setCustomValidity('');
                }
            });
        }
        
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
    </script>
</body>
</html>