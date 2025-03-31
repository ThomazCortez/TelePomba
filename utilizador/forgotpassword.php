<?php
date_default_timezone_set('UTC');
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

// Carregar autoload do Composer para PHPMailer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Função para limpar e validar entrada
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

// Variáveis para armazenar mensagens
$error = '';
$success = '';
$debug_info = '';

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proteção contra CSRF
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || 
        $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
        $error = "Erro de segurança. Por favor, tente novamente.";
    } else {
        // Sanitizar e validar e-mail
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Por favor, insira um e-mail válido.";
        } else {
            try {
                // Conexão com o banco de dados usando PDO
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
                } catch (PDOException $e) {
                    error_log("Erro de conexão ao banco de dados: " . $e->getMessage());
                    $error = "Erro de conexão ao banco de dados. Entre em contato com o suporte.";
                    $debug_info = "Detalhes técnicos: " . $e->getMessage();
                    goto end_processing;
                }

                // Verificar se o e-mail existe no banco de dados
                $stmt = $pdo->prepare("SELECT id_utilizador, nome_utilizador FROM utilizadores WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();
                
                if ($user) {
                    // Gerar token seguro
                    $token = bin2hex(random_bytes(32)); // 64 caracteres
                    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    
                    // Limpar tokens anteriores do usuário
                    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = :email");
                    $stmt->execute(['email' => $email]);
                    
                    // Armazenar novo token
                    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires)");
                    $stmt->execute([
                        'email' => $email, 
                        'token' => hash('sha256', $token), 
                        'expires' => $expires
                    ]);
                    
                    // Criar link de redefinição seguro
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                    $reset_link = "{$protocol}://{$_SERVER['HTTP_HOST']}" . dirname($_SERVER['PHP_SELF']) . "/resetpassword.php?token=" . urlencode($token);
                    
                    // Configurar PHPMailer
                    $mail = new PHPMailer(true);
                    
                    try {
                        // Configurações de debug do PHPMailer
                        $mail->SMTPDebug = 0; // Modo de debug desligado para produção
                        
                        // Configurações do servidor SMTP
                        $mail->isSMTP();
                        $mail->Host = $EMAIL_CONFIG['smtp_host'];
                        $mail->SMTPAuth = true;
                        $mail->Username = $EMAIL_CONFIG['smtp_username'];
                        $mail->Password = $EMAIL_CONFIG['smtp_password'];
                        $mail->SMTPSecure = $EMAIL_CONFIG['smtp_secure'];
                        $mail->Port = $EMAIL_CONFIG['smtp_port'];
                        
                        // Configurações do e-mail
                        $mail->setFrom($EMAIL_CONFIG['smtp_username'], 'TelePomba');
                        $mail->addAddress($email, $user['nome_utilizador']);
                        $mail->isHTML(true);
                        $mail->Subject = 'Redefinição de Palavra-passe';
                        
                        // Corpo do e-mail com Bootstrap
                        $mail->Body = "
                            <html>
                            <body style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                                <div style='background-color: #2a7d5a; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;'>
                                    <h1 style='margin: 0;'>Redefinição de Palavra-passe</h1>
                                </div>
                                <div style='background-color: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px; border: 1px solid #ddd; border-top: none;'>
                                    <p>Olá {$user['nome_utilizador']},</p>
                                    <p>Recebemos um pedido para redefinir a sua palavra-passe. Clique no botão abaixo para continuar:</p>
                                    <div style='text-align: center; margin: 30px 0;'>
                                        <a href='$reset_link' style='background-color: #2a7d5a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>Redefinir Palavra-passe</a>
                                    </div>
                                    <p style='margin-bottom: 5px;'>Se não solicitou esta redefinição, ignore este e-mail.</p>
                                    <p style='color: #6c757d;'><small>O link expirará em 1 hora.</small></p>
                                    <hr style='border-top: 1px solid #ddd; margin: 20px 0;'>
                                    <p style='color: #6c757d; font-size: 0.9em; text-align: center;'>TelePomba &copy; " . date('Y') . "</p>
                                </div>
                            </body>
                            </html>
                        ";
                        
                        $mail->AltBody = "Olá {$user['nome_utilizador']},\n\nPara redefinir a sua palavra-passe, acesse: $reset_link\n\nSe não solicitou esta redefinição, ignore este e-mail.\n\nO link expirará em 1 hora.";
                        
                        // Enviar e-mail
                        $mail->send();
                        
                        // Mensagem de sucesso genérica
                        $success = "Se o e-mail existir em nosso sistema, você receberá instruções de redefinição.";
                    } catch (Exception $e) {
                        // Log do erro sem expor detalhes ao usuário
                        error_log("Erro ao enviar e-mail: " . $e->getMessage());
                        
                        $error = "Erro ao processar seu pedido. Por favor, tente novamente mais tarde.";
                        $debug_info = "Detalhes técnicos: " . $e->getMessage();
                    }
                } else {
                    // Mensagem genérica para prevenir enumeração de e-mails
                    $success = "Se o e-mail existir em nosso sistema, você receberá instruções de redefinição.";
                }
            } catch (PDOException $e) {
                error_log("Erro de banco de dados: " . $e->getMessage());
                $error = "Erro interno do sistema. Por favor, tente novamente mais tarde.";
                $debug_info = "Detalhes técnicos: " . $e->getMessage();
            }
        }
    }
}

// Label para pular processamento em caso de erro crítico
end_processing:

// Gerar token CSRF se não existir
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci-me da palavra-passe - TelePomba</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/forgotpassword.css">
</head>
<body>
    <div class="page-background"></div>
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="card forgot-password-container border-0 shadow-lg" style="max-width: 500px; width: 100%;">
            <div class="card-header text-white text-center py-4 border-0 rounded-top">
                <h3 class="mb-0"><i class="bi bi-lock-fill me-2"></i>Esqueci-me da palavra-passe</h3>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo nl2br(htmlspecialchars($error . "\n\n" . $debug_info)); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="text-center mb-4">
                    </div>
                <?php else: ?>
                    <div class="text-center mb-4">
                        <i class="bi bi-envelope-exclamation text-primary" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Introduza o seu endereço de e-mail abaixo e enviar-lhe-emos um link para repor a palavra-passe.</p>
                    </div>
                    
                    <form method="post" class="needs-validation" novalidate>
                        <!-- Token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-floating mb-4">
                            <input type="email" id="email" name="email" class="form-control" placeholder="nome@exemplo.com" required>
                            <label for="email"><i class="bi bi-envelope me-2"></i>Endereço de e-mail</label>
                            <div class="invalid-feedback">
                                Por favor, forneça um endereço de e-mail válido.
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send me-2"></i>Enviar link para reposição
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
                
                <div class="text-center mt-4">
                    <a href="login.php" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Voltar para o login
                    </a>
                </div>
            </div>
            
            <div class="card-footer bg-transparent text-center py-3 text-muted">
                <small>&copy; <?php echo date('Y'); ?> TelePomba. Todos os direitos reservados.</small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Form validation script -->
    <script>
    (function () {
        'use strict'
        
        // Fetch all forms that need validation
        var forms = document.querySelectorAll('.needs-validation')
        
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    
                    form.classList.add('was-validated')
                }, false)
            })
    })()
    </script>
</body>
</html>