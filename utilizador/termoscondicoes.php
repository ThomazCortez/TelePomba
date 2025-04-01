<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos e Condições - Telepomba</title>
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

        ol {
            padding-left: 20px;
        }

        li {
            margin-bottom: 10px;
        }

        /* Animações */
        .animate-entrance {
            opacity: 0;
            animation-duration: 1s;
            animation-fill-mode: both;
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
                        <a class="nav-link" href="#home">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Recursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Depoimentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#team">Equipa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-primary py-1 px-3 ms-2" href="/TelePomba/utilizador/login.php">Iniciar sessão</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light py-1 px-3 ms-2" href="/TelePomba/utilizador/registo.php">Criar conta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="content-container">
        <h1 class="animate__animated animate__fadeInDown">Termos e Condições de Utilização</h1>
        
        <div class="animate__animated animate__fadeInLeft animate-entrance">
            <h2>1. Aceitação dos Termos</h2>
            <p>Ao aceder e utilizar os serviços do TelePomba, o utilizador declara aceitar e estar vinculado a estes Termos e Condições. Caso não concorde, deverá abster-se de utilizar a plataforma.</p>
        </div>

        <div class="animate__animated animate__fadeInLeft animate-entrance">
            <h2>2. Regras de Utilização</h2>
            <p>O utilizador compromete-se a:</p>
            <ul>
                <li>Fornecer informações verdadeiras e atualizadas</li>
                <li>Manter a confidencialidade das credenciais de acesso</li>
                <li>Não praticar atividades ilegais ou que violem direitos de terceiros</li>
                <li>Respeitar as normas da comunidade e diretrizes de conduta</li>
            </ul>
        </div>

        <div class="animate__animated animate__fadeInRight animate-entrance">
            <h2>3. Propriedade Intelectual</h2>
            <p>Todos os direitos de propriedade intelectual relativos à plataforma são propriedade exclusiva da Telepomba. É expressamente proibido:</p>
            <ol>
                <li>Realizar engenharia reversa ou descompilar o software</li>
            </ol>
        </div>

        <div class="animate__animated animate__fadeInLeft animate-entrance">
            <h2>4. Rescisão</h2>
            <p>O TelePomba reserva-se o direito de suspender ou terminar contas que:</p>
            <ol>
                <li>Violarem estes termos e condições</li>
                <li>Praticarem atividades fraudulentas</li>
                <li>Comprometerem a segurança da plataforma</li>
            </ol>
        </div>

        <div class="animate__animated animate__fadeInRight animate-entrance">
            <h2>5. Limitação de Responsabilidade</h2>
            <p>O TelePomba não será responsável por:</p>
            <ul>
                <li>Danos indiretos ou consequenciais</li>
                <li>Perda de dados ou interrupção de serviços</li>
                <li>Conteúdo gerado por utilizadores</li>
            </ul>
        </div>

        <div class="animate__animated animate__fadeInLeft animate-entrance">
            <h2>6. Lei Aplicável</h2>
            <p>Estes termos são regidos pela legislação portuguesa.</p>
        </div>

        <div class="animate__animated animate__fadeInRight animate-entrance">
            <h2>7. Alterações</h2>
            <p>Reservamo-nos o direito de modificar estes termos a qualquer momento. Alterações serão comunicadas com 30 dias de antecedência.</p>
        </div>

        <div class="animate__animated animate__fadeInLeft animate-entrance">
            <h2>8. Contactos</h2>
            <p>Para questões relacionadas com estes termos: <br>
            Email: telepombadev@gmail.com</p>
        </div>
    </main>

    <footer class="animattelepombadev@gmail.come__animated animate__fadeInUp">
        <div class="footer-content">
            <div class="footer-section">
                <h3>TelePomba</h3>
                <p>Conectando pombas e telecomunicações desde 2025.</p>
            </div>
            
            <div class="footer-section">
                <h4>Contacto</h4>
                <p>📧 telepombadev@gmail.com</p>
                <p>📞 (+351) 939 658 201</p>
            </div>
            
            <div class="footer-section">
                <h4>Redes Sociais</h4>
                <div class="social-icons">
                    <a href="https://x.com/TelePomba"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/telepomba?igsh=c3Nva3pycWE4NWw1"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.link/5nly84"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 TelePomba - Todos os direitos reservados.</p>
            <div class="legal-links">
                <a href="/TelePomba/utilizador/politicaprivacidade.php">Política de Privacidade</a>
                <a href="/TelePomba/utilizador/termosservico.php">Termos de Serviço</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS com Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animação no scroll
        document.addEventListener("DOMContentLoaded", function(){
            const animatedElements = document.querySelectorAll('.animate-entrance');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.classList.add(entry.target.dataset.animation);
                    }
                });
            }, { threshold: 0.1 });

            animatedElements.forEach(element => {
                element.style.opacity = '0';
                observer.observe(element);
            });
        });
    </script>
</body>
</html>
