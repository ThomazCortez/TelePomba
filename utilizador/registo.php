<?php
session_start();
require_once "../config/database.php";

$alertScript = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeUtilizador = htmlspecialchars($_POST["nomeUtilizador"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"]; // Novo campo

    if (strlen($password) < 8) {
        $alertScript = '<script>showAlert("danger", "A palavra-passe deve ter pelo menos 8 caracteres");</script>';
    } elseif ($password !== $confirmPassword) { // Nova validação
        $alertScript = '<script>showAlert("danger", "As palavras-passe não coincidem");</script>';
    } else {
        try {
            // Verificar nome de utilizador
            $checkUsername = $pdo->prepare("SELECT COUNT(*) FROM utilizadores WHERE nome_utilizador = :username");
            $checkUsername->bindParam(":username", $nomeUtilizador);
            $checkUsername->execute();
            $usernameExists = $checkUsername->fetchColumn();
            
            // Verificar email
            $checkEmail = $pdo->prepare("SELECT COUNT(*) FROM utilizadores WHERE email = :email");
            $checkEmail->bindParam(":email", $email);
            $checkEmail->execute();
            $emailExists = $checkEmail->fetchColumn();
            
            if ($usernameExists > 0) {
                $alertScript = '<script>showAlert("danger", "Este nome de utilizador já está em uso");</script>';
            } elseif ($emailExists > 0) {
                $alertScript = '<script>showAlert("danger", "Este email já está registado");</script>';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO utilizadores (nome_utilizador, email, palavra_passe) VALUES (:name, :email, :password)");
                $stmt->execute([
                    ':name' => $nomeUtilizador,
                    ':email' => $email,
                    ':password' => $hashedPassword
                ]);
                
                $alertScript = '
                    <script>
                        showAlert("success", "Registo bem-sucedido! Redirecionando...");
                        setTimeout(() => { window.location.href = "login.php"; }, 2000);
                    </script>
                ';
            }
        } catch (PDOException $e) {
            $alertScript = '<script>showAlert("danger", "Erro no sistema: ' . addslashes($e->getMessage()) . '");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | TelePomba</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Background image */
        body {
            background: url('images/green.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Alert container */
        .alert-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            width: 80%;
            max-width: 800px;
        }

        /* Card styling - updated to match green theme */
        .card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 1000px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(60, 130, 100, 0.2);
        }

        .card-row {
            display: flex;
            min-height: 650px;
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .card-row {
                flex-direction: row;
            }
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            position: relative;
        }

        /* Carousel styling */
        .carousel-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }

        .carousel-item {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .carousel-item.active {
            opacity: 1;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-caption {
            position: absolute;
            bottom: 60px; /* Adjusted to move text lower */
            left: 0;
            right: 0;
            text-align: center;
            color: white;
            z-index: 10;
            padding: 0 30px;
        }

        .carousel-caption h2 {
            font-weight: 500;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            line-height: 1.4;
        }

        .carousel-caption p {
            font-size: 16px;
            opacity: 0.9;
            max-width: 80%;
            margin: 0 auto 20px;
            line-height: 1.6;
        }

        /* Nome TelePomba no canto superior esquerdo */
        .carousel-logo {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            z-index: 20;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Form styling - updated with green theme */
        .form-container {
            padding: 45px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(240, 255, 240, 0.85));
        }

        .form-title {
            color: #2a7d5a;
            font-weight: 600;
        }

        .form-subtitle {
            color: #3d8066;
            font-size: 16px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #c5e1d3;
            background-color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: #2a7d5a;
            box-shadow: 0 0 0 0.2rem rgba(42, 125, 90, 0.25);
            background-color: #fff;
        }

        .btn {
            border-radius: 8px;
            padding: 12px 15px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2a7d5a, #256b4d);
            border: none;
            width: 100%;
            box-shadow: 0 4px 15px rgba(42, 125, 90, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #256b4d, #1e5b40);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 125, 90, 0.4);
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .auth-divider {
        text-align: center;
        margin: 25px 0;
        color: #2a7d5a;
        position: relative;
        font-size: 14px;
    }

    .auth-divider::before, .auth-divider::after {
        content: "";
        display: inline-block;
        width: 30%; /* Ajustado para 30% para deixar mais espaço para o texto */
        height: 1px;
        background: #c5e1d3;
        position: absolute;
        top: 50%;
    }   

        .auth-divider::before {
            left: 0;
        }

        .auth-divider::after {
            right: 0;
        }

        .social-login .btn {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid #e0f0e8;
            background-color: rgba(255, 255, 255, 0.9);
            color: #2a7d5a;
        }

        .social-login .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background-color: #fff;
        }

        .social-login .btn img {
            margin-right: 10px;
            vertical-align: middle;
        }

        .form-check-label {
            font-size: 14px;
            color: #2a7d5a;
        }

        .login-link {
            color: #2a7d5a;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: #1e5b40;
            text-decoration: underline;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #2a7d5a;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #1e5b40;
        }

        .back-link {
            position: absolute;
            top: 30px;
            right: 30px;
            color: #fff;
            text-decoration: none;
            z-index: 10;
            font-size: 15px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .back-link i {
            margin-right: 5px;
        }

        .back-link:hover {
            transform: translateX(-5px);
            text-decoration: none;
            color: #fff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
        }

        .dots {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .dot {
            height: 10px;
            width: 10px;
            margin: 0 7px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background-color: white;
            transform: scale(1.2);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .card-row {
                flex-direction: column;
            }

            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .carousel-container {
                border-radius: 16px 16px 0 0;
            }

            .form-container {
                padding: 30px;
            }

            .carousel-caption {
                bottom: 30px; /* Lower position for mobile */
            }

            .carousel-caption h2 {
                font-size: 24px; /* Smaller heading on mobile */
            }

            .carousel-caption p {
                font-size: 14px; /* Smaller paragraph on mobile */
            }
        }
    </style>
</head>
<body>
    <!-- Alert container for Bootstrap alerts -->
    <div class="alert-container" id="alertContainer"></div>

    <div class="container">
        <div class="card animate__animated animate__fadeIn">
            <div class="row g-0 card-row">
                <!-- Left side with image carousel -->
                <div class="col-md-6">
                    <div class="carousel-container">
                        <!-- Nome TelePomba no canto superior esquerdo -->
                        <div class="carousel-logo">TelePomba</div>

                        <div class="carousel-item active animate__animated animate__fadeIn">
                            <img src="https://images.unsplash.com/photo-1547234935-80c7145ec969?q=80&w=1000" alt="Slide 1">
                            <div class="carousel-caption">
                                <h2 class="animate__animated animate__fadeIn">Conecte-se com amigos,<br>compartilhe momentos</h2>
                                <p class="animate__animated animate__fadeIn animate__delay-1s">Envie mensagens, crie grupos e mantenha-se próximo de quem mais importa.</p>
                            </div>
                        </div>
                        <div class="carousel-item animate__animated">
                            <img src="https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?q=80&w=1000" alt="Slide 2">
                            <div class="carousel-caption">
                                <h2 class="animate__animated">Compartilhe ideias,<br>crie conexões</h2>
                                <p class="animate__animated animate__delay-1s">Partilhe pensamentos, fotos e vídeos com a sua comunidade.</p>
                            </div>
                        </div>
                        <div class="carousel-item animate__animated">
                            <img src="https://images.unsplash.com/photo-1554080353-a576cf803bda?q=80&w=1000" alt="Slide 3">
                            <div class="carousel-caption">
                                <h2 class="animate__animated">Inspire e seja inspirado,<br>todos os dias</h2>
                                <p class="animate__animated animate__delay-1s">Descubra novas histórias e mantenha-se conectado com o mundo.</p>
                            </div>
                        </div>
                    </div>
                    <div class="dots">
                        <span class="dot active" onclick="currentSlide(1)"></span>
                        <span class="dot" onclick="currentSlide(2)"></span>
                        <span class="dot" onclick="currentSlide(3)"></span>
                    </div>
                </div>

                <!-- Right side with form -->
                <div class="col-md-6">
                    <div class="form-container">
                        <h2 class="form-title">Crie a sua conta</h2>
                        <p class="form-subtitle">Junte-se à nossa comunidade hoje e comece a partilhar os seus momentos</p>
                        
                        <form method="POST" class="mt-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="nomeUtilizador" placeholder="Nome de utilizador" required>
                                <label for="username">Nome de utilizador</label>
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                <label for="email">Endereço de email</label>
                            </div>
                            
                            <div class="form-floating mb-4 password-container">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Palavra-passe" required>
                                <label for="password">Palavra-passe</label>
                                <span class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="toggleIcon_password"></i>
                                </span>
                            </div>

                            <div class="form-floating mb-4 password-container">
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirmar palavra-passe" required>
                                <label for="confirmPassword">Confirmar palavra-passe</label>
                                <span class="toggle-password" onclick="togglePassword('confirmPassword')">
                                    <i class="fas fa-eye" id="toggleIcon_confirmPassword"></i>
                                </span>
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">Concordo com os <a href="termoscondicoes.php" class="login-link">Termos e Condições</a></label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary py-3 animate__animated animate__pulse">Criar conta</button>
                        </form>
                        <p class="text-center mt-4">
                            Já tem uma conta? <a href="login.php" class="login-link">Iniciar sessão</a>
                        </p>
                        <p class="text-center">
                            Clique <a href="../index.php" class="login-link">aqui</a> para voltar à página inicial.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para mostrar alertas
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} animate__animated animate__fadeInDown`;
            alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`;
            alertContainer.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.classList.remove('animate__fadeInDown');
                alertDiv.classList.add('animate__fadeOutUp');
                setTimeout(() => alertDiv.remove(), 1000);
            }, 5000);
        }
        
        // Função para alternar a visibilidade da senha
        function togglePassword(fieldId) {
            var passwordInput = document.getElementById(fieldId);
            var toggleIcon = document.getElementById('toggleIcon_' + fieldId);
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
        
        // Slide Images (Directly in HTML/CSS)
        const slideImages = [
            "https://images.unsplash.com/photo-1547234935-80c7145ec969?q=80&w=1000",
            "https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?q=80&w=1000",
            "https://images.unsplash.com/photo-1554080353-a576cf803bda?q=80&w=1000"
        ];

        const slideTexts = [
            {
                title: "Conecte-se com amigos,<br>compartilhe momentos",
                description: "Envie mensagens, crie grupos e mantenha-se próximo de quem mais importa."
            },
            {
                title: "Compartilhe ideias,<br>crie conexões",
                description: "Partilhe pensamentos, fotos e vídeos com a sua comunidade."
            },
            {
                title: "Inspire e seja inspirado,<br>todos os dias",
                description: "Descubra novas histórias e mantenha-se conectado com o mundo."
            }
        ];

        let slideIndex = 1;
        let slideInterval;

        // Initialize the slideshow when page loads
        window.onload = function() {
            startSlideshow();
        };

        function startSlideshow() {
            // Show first slide right away
            showSlide(1);
            
            // Set interval for automatic slide changing
            slideInterval = setInterval(() => {
                slideIndex++;
                if (slideIndex > 3) slideIndex = 1;
                showSlide(slideIndex);
            }, 8000);
        }

        function currentSlide(n) {
            // Clear any existing interval
            clearInterval(slideInterval);
            
            // Show the selected slide
            showSlide(slideIndex = n);
            
            // Restart the interval
            slideInterval = setInterval(() => {
                slideIndex++;
                if (slideIndex > 3) slideIndex = 1;
                showSlide(slideIndex);
            }, 8000);
        }

        function showSlide(n) {
            const slides = document.querySelectorAll('.carousel-item');
            const dots = document.querySelectorAll('.dot');

            // Update slide index if needed
            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }

            // Remove active class from all slides and dots
            for (let i = 0; i < slides.length; i++) {
                slides[i].classList.remove("active");
            }
            for (let i = 0; i < dots.length; i++) {
                dots[i].classList.remove("active");
            }

            // Add active class to current slide and dot
            slides[slideIndex - 1].classList.add("active");
            dots[slideIndex - 1].classList.add("active");

            // Reset and replay animations
            const heading = slides[slideIndex - 1].querySelector('h2');
            const paragraph = slides[slideIndex - 1].querySelector('p');

            heading.classList.remove('animate__fadeIn');
            paragraph.classList.remove('animate__fadeIn');

            // Force reflow
            void heading.offsetWidth;
            void paragraph.offsetWidth;

            // Add animations back
            heading.classList.add('animate__fadeIn');
            paragraph.classList.add('animate__fadeIn', 'animate__delay-1s');
        }
        
        // Animação do botão ao passar o mouse
        document.querySelector('.btn-primary').addEventListener('mouseenter', function() {
            this.classList.add('animate__pulse');
        });
        
        document.querySelector('.btn-primary').addEventListener('mouseleave', function() {
            this.classList.remove('animate__pulse');
        });
    </script>

     <!-- Exibir alertas após carregar todas as funções -->
     <?= $alertScript ?>
     
</body>
</html>