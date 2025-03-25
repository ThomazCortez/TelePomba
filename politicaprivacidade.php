<!-- politicaprivacidade.php -->
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - Telepomba</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .navbar {
            background-color: var(--primary-color);
        }

        footer {
            margin-top: auto;
            background: #2a6b5f;
            color: white;
            padding: 40px 20px;
            box-shadow: 0px -4px 15px rgba(0, 0, 0, 0.3);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: space-between;
        }

        .footer-section {
            flex: 1;
            min-width: 250px;
            padding: 15px;
            text-align: center;
        }

        .social-icons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .social-icons a {
            color: white;
            font-size: 24px;
            transition: transform 0.3s ease;
        }

        .social-icons a:hover {
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .legal-links {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .legal-links a {
            color: white !important;
            text-decoration: none;
            font-size: 0.9em;
        }

        .legal-links a:hover {
            text-decoration: underline;
            color: white !important;
        }

        /* CSS específico da página */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-container {
            flex: 1;
            padding: 20px;
            max-width: 800px;
            margin: 80px auto 40px;
        }

        h1 {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        h2 {
            color: var(--primary-color);
            margin: 25px 0 15px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
            }
            .legal-links {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header igual ao index.php -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/TelePomba/index.php">
                <i class="fas fa-dove me-2"></i>
                TelePomba
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/TelePomba/index.php#home">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/TelePomba/index.php#about">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/TelePomba/index.php#features">Recursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-primary py-1 px-3 ms-2" 
                           href="/TelePomba/ficheiros/login/login.php">Iniciar sessão</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light py-1 px-3 ms-2" 
                           href="/TelePomba/ficheiros/login/registo.php">Criar conta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="content-container">
        <h1>Política de Privacidade</h1>
        
        <h2>1. Recolha de Informações</h2>
        <p>Recolhemos informações pessoais quando se regista no nosso serviço, incluindo nome, correio eletrónico e dados de utilização. Estas informações são utilizadas exclusivamente para prestar e melhorar os nossos serviços.</p>

        <h2>2. Utilização dos Dados</h2>
        <p>Os seus dados são utilizados para personalizar a sua experiência, processar transações e enviar comunicações relevantes. Nunca partilhamos as suas informações com terceiros sem o seu consentimento explícito.</p>

        <h2>3. Cookies</h2>
        <p>Utilizamos cookies para melhorar a navegação e personalizar conteúdos. Pode gerir as preferências de cookies através das definições do seu navegador.</p>

        <h2>4. Segurança</h2>
        <p>Implementamos medidas de segurança físicas e eletrónicas para proteger as suas informações, incluindo encriptação SSL e sistemas de armazenamento seguros.</p>

        <h2>5. Alterações</h2>
        <p>Esta política poderá ser atualizada periodicamente. Alterações significativas serão comunicadas através dos nossos canais oficiais.</p>
    </main>

    <!-- Footer igual ao index.php -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Telepomba</h3>
                <p>Conectando pombas e telecomunicações desde 2025</p>
            </div>
            
            <div class="footer-section">
                <h4>Contacto</h4>
                <p>📧 contato@telepomba.com</p>
                <p>📞 (+351) 939 658 201</p>
            </div>
            
            <div class="footer-section">
                <h4>Redes Sociais</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 Telepomba - Todos os direitos reservados</p>
            <div class="legal-links">
                <a href="/TelePomba/politicaprivacidade.php">Política de Privacidade</a>
                <a href="/TelePomba/termosservico.php">Termos de Serviço</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS com Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>