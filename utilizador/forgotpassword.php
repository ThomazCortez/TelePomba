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

// Carregar autoload do Composer para PHPMailer
require '../vendor/autoload.php';

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
                    
                    // Criar link de redefinição seguro - CORREÇÃO PRINCIPAL AQUI
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
                        
                        // Corpo do e-mail
                        $mail->Body = "
                            <html>
                            <body style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                                <h1 style='color: #2a7d5a;'>Redefinição de Palavra-passe</h1>
                                <p>Olá {$user['nome_utilizador']},</p>
                                <p>Recebemos um pedido para redefinir a sua palavra-passe. Clique no link abaixo para continuar:</p>
                                <p><a href='$reset_link' style='background-color: #2a7d5a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Redefinir Palavra-passe</a></p>
                                <p>Se não solicitou esta redefinição, ignore este e-mail.</p>
                                <p>O link expirará em 1 hora.</p>
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
    <title>Esqueci a minha palavra-passe - TelePomba</title>
    <link rel="stylesheet" href="css/forgotpassword.css">
</head>
<body>
    <div class="forgot-password-container">
        <div class="forgot-password-header">
            <h1>Esqueci a minha palavra-passe</h1>
        </div>
        
        <div class="forgot-password-content">
            <?php if (!empty($error)): ?>
                <div class="message error-message">
                    <?php 
                    // Em desenvolvimento, exiba detalhes de erro
                    echo nl2br(htmlspecialchars($error . "\n\n" . $debug_info)); 
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="message success-message"><?php echo $success; ?></div>
            <?php else: ?>
                <p>Digite o seu endereço de e-mail abaixo e enviaremos um link para redefinir a sua palavra-passe.</p>
                
                <form method="post">
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn-submit">Enviar Link de Redefinição</button>
                </form>
            <?php endif; ?>
            
            <div class="back-to-login">
                <a href="login.php">Voltar para o login</a>
            </div>
        </div>
    </div>
</body>
</html>