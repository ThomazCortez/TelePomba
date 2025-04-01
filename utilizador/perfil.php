<?php
// Start session
session_start();

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

// Get user ID from session
$id_utilizador = $_SESSION['user_id'];

// Initialize variables for user data
$userDetails = [];
$error_message = '';
$success_message = '';

// Fetch user data from database
try {
    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE id_utilizador = :id_utilizador");
    $stmt->bindParam(':id_utilizador', $id_utilizador, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $error_message = "Usuário não encontrado.";
        // Redirect to login page if user not found
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    $error_message = "Erro ao buscar dados do usuário: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize update data array
    $updateData = [];
    $updateFields = [];
    
    // Check if name was changed
    if (isset($_POST['nome_utilizador']) && $_POST['nome_utilizador'] !== $userDetails['nome_utilizador']) {
        $updateData['nome_utilizador'] = $_POST['nome_utilizador'];
        $updateFields[] = 'nome_utilizador = :nome_utilizador';
    }
    
    // Check if email was changed
    if (isset($_POST['email']) && $_POST['email'] !== $userDetails['email']) {
        // Verify if email is already in use
        $checkEmail = $pdo->prepare("SELECT id_utilizador FROM utilizadores WHERE email = :email AND id_utilizador != :id_utilizador");
        $checkEmail->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $checkEmail->bindParam(':id_utilizador', $id_utilizador, PDO::PARAM_INT);
        $checkEmail->execute();
        
        if ($checkEmail->rowCount() > 0) {
            $error_message = "Este email já está em uso por outro usuário.";
        } else {
            $updateData['email'] = $_POST['email'];
            $updateFields[] = 'email = :email';
        }
    }
    
    // Check if description was changed
    if (isset($_POST['descricao']) && $_POST['descricao'] !== $userDetails['descricao']) {
        $updateData['descricao'] = $_POST['descricao'];
        $updateFields[] = 'descricao = :descricao';
    }
    
    // Handle password change
    if (!empty($_POST['palavra_passe_atual']) && !empty($_POST['palavra_passe_nova']) && !empty($_POST['palavra_passe_confirmacao'])) {
        // Verify current password
        if (password_verify($_POST['palavra_passe_atual'], $userDetails['palavra_passe'])) {
            // Check if new passwords match
            if ($_POST['palavra_passe_nova'] === $_POST['palavra_passe_confirmacao']) {
                // Hash new password
                $hashed_password = password_hash($_POST['palavra_passe_nova'], PASSWORD_DEFAULT);
                $updateData['palavra_passe'] = $hashed_password;
                $updateFields[] = 'palavra_passe = :palavra_passe';
            } else {
                $error_message = "As novas senhas não coincidem.";
            }
        } else {
            $error_message = "Senha atual incorreta.";
        }
    }
    
    // Handle profile image
    if (isset($_FILES['imagem_perfil']) && $_FILES['imagem_perfil']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['imagem_perfil']['tmp_name'];
        $file_name = $_FILES['imagem_perfil']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Check file extension
        $allowed_exts = ['jpg', 'jpeg', 'png'];
        if (in_array($file_ext, $allowed_exts)) {
            // Check file size (1MB max)
            if ($_FILES['imagem_perfil']['size'] <= 1048576) {
                // Create unique filename
                $new_filename = uniqid('profile_') . '.' . $file_ext;
                $upload_path = '../ficheiros/media/profiles/' . $new_filename;  // Corrected path
                
                // Ensure directory exists
                if (!file_exists(dirname($upload_path))) {
                    mkdir(dirname($upload_path), 0777, true);
                }
                
                // Move uploaded file
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Delete old image if not default
                    if ($userDetails['imagem_perfil'] !== '../ficheiros/media/profiles/default.png') {  // Updated path
                        $old_path = $userDetails['imagem_perfil'];
                        if (file_exists($old_path)) {
                            unlink($old_path);
                        }
                    }
                    
                    $image_path = 'ficheiros/media/profiles/' . $new_filename;  // Relative path for DB
                    $updateData['imagem_perfil'] = $image_path;
                    $updateFields[] = 'imagem_perfil = :imagem_perfil';
                }
            }
        }
    } elseif (isset($_POST['delete_image']) && $_POST['delete_image'] === '1') {
        // Reset to default image
        $updateData['imagem_perfil'] = 'ficheiros/media/profiles/default.png';  // Relative path
        $updateFields[] = 'imagem_perfil = :imagem_perfil';
    }
    
    
    // Update database if there are changes
    if (!empty($updateFields) && empty($error_message)) {
        try {
            // Prepare update query
            $sql = "UPDATE utilizadores SET " . implode(', ', $updateFields) . ", atualizado_em = NOW() WHERE id_utilizador = :id_utilizador";
            $stmt = $pdo->prepare($sql);
            
            // Bind parameters
            foreach ($updateData as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindParam(':id_utilizador', $id_utilizador, PDO::PARAM_INT);
            
            // Execute update
            if ($stmt->execute()) {
                $success_message = "Definições atualizadas com sucesso!";
                
                // Refresh user data
                $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE id_utilizador = :id_utilizador");
                $stmt->bindParam(':id_utilizador', $id_utilizador, PDO::PARAM_INT);
                $stmt->execute();
                $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error_message = "Erro ao atualizar as definições.";
            }
        } catch (PDOException $e) {
            $error_message = "Erro ao atualizar as definições: " . $e->getMessage();
        }
    } elseif (empty($updateFields) && empty($error_message)) {
        $success_message = "Nenhuma alteração foi feita.";
    }
    
    // Handle account deactivation
    if (isset($_POST['deactivate_account']) && $_POST['deactivate_account'] === '1') {
        if (!empty($_POST['deactivate_password'])) {
            // Verify password
            if (password_verify($_POST['deactivate_password'], $userDetails['palavra_passe'])) {
                try {
                    // Update user status instead of deleting
                    $stmt = $pdo->prepare("UPDATE utilizadores SET estado = 'inativo' WHERE id_utilizador = :id_utilizador");
                    $stmt->bindParam(':id_utilizador', $id_utilizador, PDO::PARAM_INT);
                    
                    if ($stmt->execute()) {
                        // Destroy session
                        session_destroy();
                        
                        // Redirect to login page
                        header('Location: login.php?deactivated=true');
                        exit;
                    } else {
                        $error_message = "Erro ao desativar a conta.";
                    }
                } catch (PDOException $e) {
                    $error_message = "Erro ao desativar a conta: " . $e->getMessage();
                }
            } else {
                $error_message = "Senha incorreta. Não foi possível desativar a conta.";
            }
        } else {
            $error_message = "Por favor, insira sua senha para confirmar a desativação da conta.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TelePomba - Definições de Conta</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2a7d5a;
            --primary-dark: #1e5b40;
            --background-light: #f4f7f6;
            --text-primary: #2c3e50;
            --text-secondary: #3d8066;
            --border-light: #c5e1d3;
            --bs-primary: #2a7d5a;
            --bs-primary-rgb: 42, 125, 90;
            --bs-btn-hover-bg: #1e5b40;
            --bs-btn-hover-border-color: #1e5b40;
        }
        
        body {
            background-color: var(--background-light);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('images/green.png'); /* Update with your image path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
        
        .settings-container {
            max-width: 800px;
            margin: 2rem auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            overflow: hidden;
            padding: 0; /* Add this to remove any potential padding */
        }
        
        .settings-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            position: relative;
            width: 100%; /* Ensure full width */
            margin: 0; /* Remove any margin */
            border-top-left-radius: 12px; /* Match container's border radius */
            border-top-right-radius: 12px;
        }
        
        .settings-content {
            padding: 2rem;
        }
        
        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--border-light);
            box-shadow: 0 0 0 0.25rem rgba(42, 125, 90, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .section-divider {
            height: 1px;
            background-color: var(--border-light);
            margin: 2rem 0;
        }
        
        .profile-image-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            position: relative;
        }
        
        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-upload-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(42, 125, 90, 0.8);
            color: white;
            text-align: center;
            padding: 0.25rem;
            opacity: 0;
            transition: opacity 0.3s;
            cursor: pointer;
        }
        
        .profile-image-container:hover .image-upload-overlay {
            opacity: 1;
        }
        
        .card {
            border-color: var(--border-light);
            border-radius: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(42, 125, 90, 0.1);
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }
        
        .danger-zone {
            background-color: #fff8f8;
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid #ffcdd2;
        }
        
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        
        .form-floating label {
            color: var(--text-secondary);
        }
        
        .alert {
            animation-duration: 0.5s;
        }
    </style>
</head>
<body>
    <div class="container settings-container animate__animated animate__fadeIn">
        <div class="settings-header">
            <!-- Added Back Button -->
            <a href="../home.php" class="btn btn-light btn-sm position-absolute start-0 ms-3">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <h1 class="fs-4 mb-0 text-center">Definições da Conta</h1>
        </div>
        
        <div class="settings-content">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger animate__animated animate__fadeIn" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success animate__animated animate__fadeIn" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <form id="settingsForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                <!-- Perfil Section -->
                <h2 class="section-title">Perfil</h2>
                
                <div class="row align-items-center mb-4">
                    <div class="col-md-auto">
                        <div class="profile-image-container animate__animated animate__pulse">
                        <img src="../<?= htmlspecialchars($userDetails['imagem_perfil']); ?>" id="profileImagePreview" class="profile-image" alt="Imagem de Perfil">
                            <label for="profileImage" class="image-upload-overlay">
                                <i class="fas fa-camera"></i> Alterar
                            </label>
                            <input type="file" id="profileImage" name="imagem_perfil" class="d-none" accept="image/png,image/jpeg">
                        </div>
                    </div>
                    <div class="col">
                        <p class="text-muted mb-1">Imagem de Perfil</p>
                        <div class="d-flex gap-2">
                            <button type="button" id="uploadImageBtn" class="btn btn-sm btn-outline-primary animate__animated animate__fadeIn">
                                <i class="fas fa-upload"></i> Carregar Imagem
                            </button>
                            <button type="button" id="removeImageBtn" class="btn btn-sm btn-outline-secondary animate__animated animate__fadeIn">
                                <i class="fas fa-trash-alt"></i> Remover
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">Formatos suportados: JPG, PNG. Tamanho máximo: 1MB</small>
                    </div>
                </div>
                
                <div class="row g-3 mb-4 animate__animated animate__fadeInUp" style="--animate-delay: 0.1s;">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="nomeUtilizador" name="nome_utilizador" placeholder="Nome de Utilizador" value="<?php echo htmlspecialchars($userDetails['nome_utilizador']); ?>" required>
                            <label for="nomeUtilizador">Nome de Utilizador</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($userDetails['email']); ?>" required>
                            <label for="email">Email</label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4 animate__animated animate__fadeInUp" style="--animate-delay: 0.2s;">
                    <div class="form-floating">
                        <textarea class="form-control" id="descricao" name="descricao" style="height: 100px" placeholder="Descrição"><?php echo htmlspecialchars($userDetails['descricao']); ?></textarea>
                        <label for="descricao">Descrição</label>
                    </div>
                    <small class="text-muted">Uma breve descrição sobre você que aparecerá no seu perfil.</small>
                </div>
                
                <div class="section-divider"></div>
                
                <!-- Segurança Section -->
                <h2 class="section-title animate__animated animate__fadeInUp" style="--animate-delay: 0.3s;">Segurança</h2>
                
                <div class="card mb-4 animate__animated animate__fadeInUp" style="--animate-delay: 0.4s;">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Alterar Palavra-passe</h5>
                        
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Palavra-passe Atual</label>
                            <input type="password" class="form-control" id="currentPassword" name="palavra_passe_atual">
                        </div>
                        
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Nova Palavra-passe</label>
                            <input type="password" class="form-control" id="newPassword" name="palavra_passe_nova">
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmar Nova Palavra-passe</label>
                            <input type="password" class="form-control" id="confirmPassword" name="palavra_passe_confirmacao">
                        </div>
                        
                        <div class="progress mb-3" style="height: 6px;">
                            <div id="passwordStrength" class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <small id="passwordStrengthText" class="text-muted">A força da sua palavra-passe será exibida aqui</small>
                    </div>
                </div>
                
                <div class="section-divider"></div>
                
                <!-- Danger Zone -->
                <div class="danger-zone animate__animated animate__fadeInUp" style="--animate-delay: 0.5s;">
                    <h3 class="fs-5 text-danger mb-3">Zona de Perigo</h3>
                    <p class="text-muted">Ações destrutivas que não podem ser desfeitas</p>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="fas fa-exclamation-triangle"></i> Desativar Conta
                    </button>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4 animate__animated animate__fadeInUp" style="--animate-delay: 0.6s;">
                    <a href="index.php" class="btn btn-outline-primary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Desativar Conta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Aviso: Esta ação não pode ser desfeita.
                        </div>
                        <p>Ao desativar sua conta:</p>
                        <ul>
                            <li>Seus dados pessoais serão removidos</li>
                            <li>Suas mensagens serão arquivadas</li>
                            <li>Você não poderá mais acessar sua conta</li>
                        </ul>
                        <div class="mb-3">
                            <label for="deactivatePassword" class="form-label">Digite sua palavra-passe para confirmar:</label>
                            <input type="password" class="form-control" id="deactivatePassword" name="deactivate_password" required>
                        </div>
                        <input type="hidden" name="deactivate_account" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Desativar Minha Conta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile Image Preview
            const profileImage = document.getElementById('profileImage');
            const profileImagePreview = document.getElementById('profileImagePreview');
            const uploadImageBtn = document.getElementById('uploadImageBtn');
            const removeImageBtn = document.getElementById('removeImageBtn');
            
            // Upload image button click
            uploadImageBtn.addEventListener('click', function() {
                profileImage.click();
            });
            
            // Remove image button click
            removeImageBtn.addEventListener('click', function() {
                profileImagePreview.src = 'ficheiros/media/profiles/default.png';
                profileImage.value = '';
                
                // Add a hidden input to signal deletion
                const deleteImageInput = document.createElement('input');
                deleteImageInput.type = 'hidden';
                deleteImageInput.name = 'delete_image';
                deleteImageInput.value = '1';
                
                // Remove existing delete_image input if any
                const existingDeleteInput = document.querySelector('input[name="delete_image"]');
                if (existingDeleteInput) {
                    existingDeleteInput.remove();
                }
                
                document.getElementById('settingsForm').appendChild(deleteImageInput);
            });
            
            // Preview image when selected
            profileImage.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        profileImagePreview.src = e.target.result;
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                    
                    // Remove delete_image input if exists
                    const existingDeleteInput = document.querySelector('input[name="delete_image"]');
                    if (existingDeleteInput) {
                        existingDeleteInput.remove();
                    }
                }
            });
            
            // Password strength meter
            const newPassword = document.getElementById('newPassword');
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordStrengthText = document.getElementById('passwordStrengthText');
            
            newPassword.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let feedback = '';
                
                if (password.length > 6) strength += 20;
                if (password.length > 10) strength += 20;
                if (password.match(/[a-z]+/)) strength += 20;
                if (password.match(/[A-Z]+/)) strength += 20;
                if (password.match(/[0-9]+/)) strength += 20;
                if (password.match(/[^a-zA-Z0-9]+/)) strength += 20;
                
                passwordStrength.style.width = strength + '%';
                
                if (strength < 40) {
                    passwordStrength.className = 'progress-bar bg-danger';
                    feedback = 'Fraca';
                } else if (strength < 70) {
                    passwordStrength.className = 'progress-bar bg-warning';
                    feedback = 'Média';
                } else {
                    passwordStrength.className = 'progress-bar bg-success';
                    feedback = 'Forte';
                }
                
                passwordStrengthText.textContent = 'Força da palavra-passe: ' + feedback;
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    alert.classList.add('animate__fadeOut');
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                });
            }, 5000);
            
            // Animation for elements
            animateElements();
            
            // Function to animate elements sequentially
            function animateElements() {
                const animatedElements = document.querySelectorAll('.animate__animated');
                
                animatedElements.forEach((element, index) => {
                    element.style.animationDelay = `${index * 0.1}s`;
                });
            }
        });
    </script>
</body>
</html>