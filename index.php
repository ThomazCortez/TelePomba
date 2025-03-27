<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TelePomba - Conecte-se com amigos</title>
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
        
        html {
            scroll-snap-type: y proximity;
            scroll-behavior: smooth;
        }
        
        section {
            scroll-snap-align: start;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 0;
            position: relative;
            transition: background-color 0.5s ease;
        }

        /* Alternar fundos para seções normais */
        section:not(.hero):not(.hero-bg):nth-child(even) {
            background-color: white;
        }
        
        section:not(.hero):not(.hero-bg):nth-child(odd) {
            background-color: var(--light-gray-bg);
        }

        /* Hover effects for client and team cards */
        .team-card, 
        .client-card,
        .scrum-master-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .team-card:hover,
        .client-card:hover,
        .scrum-master-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        footer {
            scroll-snap-align: start;
        }

        /* Background image styles for hero and CTA sections */
        .hero,
        .bg-primary.text-white {
            position: relative;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
        }
        
        .navbar {
            background-color: var(--primary-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #1d504a;
            border-color: #1d504a;
        }
        
        .hero {
            background: linear-gradient(rgba(42, 107, 95, 0.7), rgba(42, 107, 95, 0.7)), url('/api/placeholder/1200/800') center/cover no-repeat;
            color: var(--light-text);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .team-card {
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
        }
        
        .scrum-master-card {
            border: 2px solid var(--primary-color);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .team-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
        }
        
        .client-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .rating {
            color: gold;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        footer {
            background-color: var(--primary-color);
            color: var(--light-text);
            padding: 30px 0;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 50px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        /* Estilos fixos para todas as imagens */
        .client-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .feature-img {
            width: 400px;
            height: 300px;
            object-fit: cover;
        }
        
        .scrum-master-img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
        }

        .hero {
            position: relative;
            color: var(--light-text);
        }

        .hero, .hero-bg {
            position: relative;
            color: var(--light-text);
        }

        .hero::before, .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0.7;
            z-index: -1;
        }

        .hero::before {
            background-image: url('ficheiros/media/img/green.jpg');
        }

        .hero-bg::before {
            background-image: url('ficheiros/media/img/blue2.jpg');
        }

        footer {
            margin-top: auto;
            background:linear-gradient(90deg, #2a6b5f, #2a6b5f);
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
        }

        .footer-section h3 {
            color: #ecf0f1;
            margin-bottom: 15px;
        }

        .footer-section h4 {
            color: #ecf0f1;
            margin-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 5px;
        }

        .social-icons {
            display: flex;
            gap: 20px;
            justify-content: center; /* Centraliza os ícones horizontalmente */
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
            color: #ecf0f1;
            text-decoration: none;
            font-size: 0.9em;
        }

        .legal-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
            
            .social-icons {
                justify-content: center;
            }
            
            .legal-links {
                flex-direction: column;
                gap: 10px;
            }
        }

        /* Classe para elementos que devem ser animados */
        .animate-on-scroll {
            opacity: 0;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        /* Classes de animação específicas */
        .animate-fadeIn {
            opacity: 0;
            transform: translateY(20px);
        }

        .animate-fadeIn.animated {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-fadeInLeft {
            opacity: 0;
            transform: translateX(-20px);
        }

        .animate-fadeInLeft.animated {
            opacity: 1;
            transform: translateX(0);
        }

        .animate-fadeInRight {
            opacity: 0;
            transform: translateX(20px);
        }

        .animate-fadeInRight.animated {
            opacity: 1;
            transform: translateX(0);
        }

        .animate-fadeInUp {
            opacity: 0;
            transform: translateY(20px);
        }

        .animate-fadeInUp.animated {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-delay-1 {
            transition-delay: 0.2s;
        }

        .animate-delay-2 {
            transition-delay: 0.4s;
        }

        .animate-delay-3 {
            transition-delay: 0.6s;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
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

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-md-10 animate-on-scroll animate-fadeIn">
                    <h1 class="display-4 fw-bold mb-4">Bem-vindo ao TelePomba</h1>
                    <p class="lead mb-5">Junte-se a amigos, crie grupos e mantenha-se próximo de quem mais importa.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="/TelePomba/utilizador/registo.php" class="btn btn-light btn-lg px-4">Criar Conta</a>
                        <a href="#about" class="btn btn-outline-light btn-lg px-4">Saiba Mais</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center animate-on-scroll animate-fadeInUp">
                    <h2 class="section-title">O que é o TelePomba?</h2>
                    <p class="lead mb-5">
                        O TelePomba é uma plataforma de mensagens instantâneas que lhe permite ligar-se a amigos e familiares de uma forma simples e segura. Envie mensagens, partilhe momentos especiais, crie grupos e mantenha-se próximo das pessoas que mais lhe importam.
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4 animate-on-scroll animate-fadeInUp animate-delay-1">
                    <div class="text-center">
                        <i class="fas fa-comments feature-icon"></i>
                        <h4>Conversas Privadas</h4>
                        <p>Conversas encriptadas de ponta a ponta para garantir a sua privacidade em todos os momentos.</p>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-fadeInUp animate-delay-2">
                    <div class="text-center">
                        <i class="fas fa-users feature-icon"></i>
                        <h4>Grupos Personalizados</h4>
                        <p>Crie grupos com até 1000 membros e organize eventos, partilhe ficheiros e coordene atividades.</p>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-fadeInUp animate-delay-3">
                    <div class="text-center">
                        <i class="fas fa-photo-video feature-icon"></i>
                        <h4>Partilha de Media</h4>
                        <p>Partilhe fotos, vídeos e documentos facilmente, mantendo a qualidade original.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 animate-on-scroll animate-fadeInLeft">
                    <img src="ficheiros/media/img/logo_telepomba.png" alt="TelePomba App" class="feature-img rounded shadow-lg" width="400" height="300">
                </div>
                <div class="col-md-6 animate-on-scroll animate-fadeInRight">
                    <h2 class="section-title text-start">Porquê usar o TelePomba?</h2>
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-3">
                            <i class="fas fa-shield-alt text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4>Segurança Incomparável</h4>
                            <p>Utilizamos a mais recente tecnologia de encriptação para garantir que as suas conversas se mantêm privadas.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-3">
                            <i class="fas fa-bolt text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4>Velocidade e Fiabilidade</h4>
                            <p>Mensagens entregues instantaneamente, mesmo com conexões de rede mais lentas.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-3">
                            <i class="fas fa-palette text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4>Personalização Total</h4>
                            <p>Personalize o seu perfil, grupos e definições para uma experiência única.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-globe text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4>Disponível em Todo o Lado</h4>
                            <p>Aceda de qualquer dispositivo – móvel, tablet ou computador, em qualquer parte do mundo.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center animate-on-scroll animate-fadeInUp">
                    <h2 class="section-title">Os Nossos Clientes Satisfeitos</h2>
                    <p class="lead mb-5">Veja o que os utilizadores do TelePomba têm a dizer sobre a nossa plataforma.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4 animate-on-scroll animate-fadeInUp animate-delay-1">
                    <div class="client-card">
                        <div class="rating">★★★★★</div>
                        <p class="fst-italic mb-4">"O TelePomba revolucionou a forma como me comunico com os meus amigos e família. A interface é intuitiva e as funcionalidades são exatamente o que eu precisava!"</p>
                        <div class="d-flex align-items-center">
                            <img src="ficheiros/media/img/odete.jpg" alt="Client" class="client-img me-3" width="60" height="60">
                            <div>
                                <h5 class="mb-0">Odete de Conceição</h5>
                                <small class="text-muted">Utilizadora desde 2025</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-fadeInUp animate-delay-2">
                    <div class="client-card">
                        <div class="rating">★★★★★</div>
                        <p class="fst-italic mb-4">"Como gestor de uma equipa remota, o TelePomba tornou-se essencial para o nosso trabalho diário. Os grupos e a partilha de ficheiros são excelentes!"</p>
                        <div class="d-flex align-items-center">
                            <img src="ficheiros/media/img/ramalho.jpg" alt="Client" class="client-img me-3" width="60" height="60">
                            <div>
                                <h5 class="mb-0">Ramalho Crispim</h5>
                                <small class="text-muted">Gestor de Projetos</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-fadeInUp animate-delay-3">
                    <div class="client-card">
                        <div class="rating">★★★★★</div>
                        <p class="fst-italic mb-4">"Adoro a segurança que o TelePomba oferece. Sinto-me confortável a partilhar informações pessoais, sabendo que estão protegidas."</p>
                        <div class="d-flex align-items-center">
                            <img src="ficheiros/media/img/ferreira.png" alt="Client" class="client-img me-3" width="60" height="60">
                            <div>
                                <h5 class="mb-0">Luzia Ferreira</h5>
                                <small class="text-muted">Especialista em Segurança</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
<section id="team">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center animate-on-scroll animate-fadeInUp">
                <h2 class="section-title">A Nossa Equipa</h2>
                <p class="lead mb-5">Conheça os talentosos profissionais por trás do TelePomba.</p>
            </div>
        </div>
        
        <!-- Scrum Master (Maior) -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 animate-on-scroll animate-fadeInUp">
                <div class="card scrum-master-card text-center p-4">
                    <img src="ficheiros/media/img/thomaz.png" class="scrum-master-img mb-3" alt="Scrum Master" width="200" height="200">
                    <h3>Thomaz Cortez</h3>
                    <p class="text-primary fw-bold">Scrum Master e Fundador</p>
                    <p>Com 0 anos de experiência no desenvolvimento de plataformas de comunicação, Thomaz lidera a equipa do TelePomba com paixão e inovação.</p>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <a href="https://github.com/ThomazCortez" class="text-primary"><i class="fab fa-github fs-4"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Desenvolvedores (10) -->
        <div class="row g-4">
            <!-- Desenvolvedor 1 -->
            <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp" style="animation-delay: 0.2s">
                <div class="card team-card text-center p-3">
                    <img src="ficheiros/media/img/marco.png" class="team-img mb-3" alt="Developer" width="150" height="150">
                    <h5>Marco Neto</h5>
                    <p class="text-primary">Co-Founder e Definições de Utilizador</p>
                </div>
            </div>
            
            <!-- Desenvolvedor 2 -->
            <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp" style="animation-delay: 0.3s">
                <div class="card team-card text-center p-3">
                    <img src="ficheiros/media/img/miguel.jpg" class="team-img mb-3" alt="Developer" width="150" height="150">
                    <h5>Miguel Duarte</h5>
                    <p class="text-primary">Imagens</p>
                </div>
            </div>
            
            <!-- Desenvolvedor 3 -->
            <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp" style="animation-delay: 0.4s">
                <div class="card team-card text-center p-3">
                    <img src="ficheiros/media/img/lourenco.png" class="team-img mb-3" alt="Developer" width="150" height="150">
                    <h5>Lourenço Mestre</h5>
                    <p class="text-primary">Footer e Base de Dados</p>
                </div>
            </div>
            
            <!-- Desenvolvedor 4 -->
            <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp" style="animation-delay: 0.5s">
                <div class="card team-card text-center p-3">
                    <img src="ficheiros/media/img/martim.png" class="team-img mb-3" alt="Developer" width="150" height="150">
                    <h5>Martim Marreiros</h5>
                    <p class="text-primary">Definições de Utilizador</p>
                </div>
            </div>
            
            <!-- Desenvolvedor 5 -->
            <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp" style="animation-delay: 0.6s">
                <div class="card team-card text-center p-3">
                    <img src="ficheiros/media/img/yat.jpg" class="team-img mb-3" alt="Developer" width="150" height="150">
                    <h5>Yathaarth Handa</h5>
                    <p class="text-primary">Index</p>
                </div>
            </div>
            
            <!-- Desenvolvedor 6 -->
            <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp" style="animation-delay: 0.7s">
                <div class="card team-card text-center p-3">
                    <img src="ficheiros/media/img/pomba.jpg" class="team-img mb-3" alt="Developer" width="150" height="150">
                    <h5>Gustavo Pomba</h5>
                    <p class="text-primary">Login, Registo e Logout</p>
                </div>
            </div>
            
            <!-- Desenvolvedor 7 -->
            <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp" style="animation-delay: 0.8s">
                <div class="card team-card text-center p-3">
                    <img src="ficheiros/media/img/mega.jpg" class="team-img mb-3" alt="Developer" width="150" height="150">
                    <h5>Gonçalo Dionísio</h5>
                    <p class="text-primary">Header e Navbar</p>
                </div>
            </div>
            
            <!-- Desenvolvedor 8 -->
            <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp" style="animation-delay: 0.9s">
                <div class="card team-card text-center p-3">
                    <img src="ficheiros/media/img/henrique.jpg" class="team-img mb-3" alt="Developer" width="150" height="150">
                    <h5>Henrique Domingos</h5>
                    <p class="text-primary">Base de Dados</p>
                </div>
            </div>
            
            <!-- Últimos 2 desenvolvedores centralizados com mais espaço -->
<div class="w-100 d-flex justify-content-center flex-wrap mt-4">
    <!-- Desenvolvedor 9 -->
    <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp mx-md-4" style="animation-delay: 1s">
        <div class="card team-card text-center p-3">
            <img src="ficheiros/media/img/rafa.jpg" class="team-img mb-3" alt="Developer" width="150" height="150">
            <h5>Rafael Costa</h5>
            <p class="text-primary">Logotípo e Imagens</p>
        </div>
    </div>
    
    <!-- Desenvolvedor 10 -->
    <div class="col-md-3 col-sm-6 animate-on-scroll animate-fadeInUp mx-md-4" style="animation-delay: 1.1s">
        <div class="card team-card text-center p-3">
            <img src="ficheiros/media/img/nic.jpg" class="team-img mb-3" alt="Developer" width="150" height="150">
            <h5>Nicolas Almeida</h5>
            <p class="text-primary">Login, Registo e Logout</p>
        </div>
    </div>
</div>
        </div>
    </div>
</section>

    <!-- CTA Section -->
    <section class="hero-bg">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-8 animate-on-scroll animate-fadeIn">
                <h2 class="mb-4">Pronto para começar?</h2>
                <p class="lead mb-5">Junte-se à nossa comunidade hoje e comece a partilhar os seus momentos.</p>
                <a href="/TelePomba/utilizador/registo.php" class="btn btn-light btn-lg px-5 py-3 animate__animated animate__pulse animate__infinite animate__slower">Criar Conta Gratuitamente</a>
            </div>
        </div>
    </div>
</section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animação ao Scroll
        document.addEventListener("DOMContentLoaded", function() {
            // Configuração do Intersection Observer
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Animar elementos dentro da seção visível
                        const animatableElements = entry.target.querySelectorAll('.animate-on-scroll');
                        animatableElements.forEach(element => {
                            element.classList.add('animated');
                        });
                    }
                });
            }, { threshold: 0.1 });
            
            // Observar todas as seções
            document.querySelectorAll('section').forEach(section => {
                observer.observe(section);
            });
            
            // Animar a primeira seção imediatamente (hero section)
            const heroSection = document.querySelector('#home');
            if (heroSection) {
                const heroElements = heroSection.querySelectorAll('.animate-on-scroll');
                heroElements.forEach(element => {
                    element.classList.add('animated');
                });
            }
        });
    </script>
</body>
</html>