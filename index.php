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
        }
        
        html {
            scroll-snap-type: y mandatory;
            scroll-behavior: smooth;
        }
        
        section {
            scroll-snap-align: start;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: #f8f9fa;
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
                        <a class="nav-link btn btn-light text-primary py-1 px-3 ms-2" href="/TelePomba/ficheiros/login/login.php">Iniciar sessão</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light py-1 px-3 ms-2" href="/TelePomba/ficheiros/login/registo.php">Criar conta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-md-10 animate__animated animate__fadeIn">
                    <h1 class="display-4 fw-bold mb-4">Bem-vindo ao TelePomba</h1>
                    <p class="lead mb-5">Conecte-se com amigos, crie grupos e mantenha-se próximo de quem mais importa.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="/TelePomba/ficheiros/login/registo.php" class="btn btn-light btn-lg px-4">Criar Conta</a>
                        <a href="#about" class="btn btn-outline-light btn-lg px-4">Saiba Mais</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center animate__animated animate__fadeInUp">
                    <h2 class="section-title">O que é o TelePomba?</h2>
                    <p class="lead mb-5">
                        O TelePomba é uma plataforma de mensagens instantâneas que permite conectar-se com amigos e familiares de forma simples e segura. Envie mensagens, partilhe momentos especiais, crie grupos e mantenha-se próximo das pessoas que mais importam para si.
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <div class="text-center">
                        <i class="fas fa-comments feature-icon"></i>
                        <h4>Conversas Privadas</h4>
                        <p>Conversas encriptadas de ponta a ponta para garantir a sua privacidade em todos os momentos.</p>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <div class="text-center">
                        <i class="fas fa-users feature-icon"></i>
                        <h4>Grupos Personalizados</h4>
                        <p>Crie grupos com até 1000 membros e organize eventos, partilhe ficheiros e coordene atividades.</p>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
                    <div class="text-center">
                        <i class="fas fa-photo-video feature-icon"></i>
                        <h4>Partilha de Media</h4>
                        <p>Partilhe fotos, vídeos e documentos facilmente com qualidade original.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 animate__animated animate__fadeInLeft">
                    <img src="ficheiros/media/index/logo_telepomba.png" alt="TelePomba App" class="feature-img rounded shadow-lg" width="400" height="300">
                </div>
                <div class="col-md-6 animate__animated animate__fadeInRight">
                    <h2 class="section-title text-start">Porquê usar o TelePomba?</h2>
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-3">
                            <i class="fas fa-shield-alt text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4>Segurança Incomparável</h4>
                            <p>Utilizamos a mais recente tecnologia de encriptação para garantir que as suas conversas permanecem privadas.</p>
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
                            <p>Acesse de qualquer dispositivo - móvel, tablet ou computador, em qualquer lugar do mundo.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center animate__animated animate__fadeInUp">
                    <h2 class="section-title">Os Nossos Clientes Satisfeitos</h2>
                    <p class="lead mb-5">Veja o que os utilizadores do TelePomba têm a dizer sobre a nossa plataforma.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <div class="client-card">
                        <div class="rating">★★★★★</div>
                        <p class="fst-italic mb-4">"O TelePomba revolucionou a forma como comunico com os meus amigos e família. A interface é intuitiva e as funcionalidades são exactamente o que eu precisava!"</p>
                        <div class="d-flex align-items-center">
                            <img src="ficheiros/media/index/odete.jpg" alt="Client" class="client-img me-3" width="60" height="60">
                            <div>
                                <h5 class="mb-0">Odete de Conceição</h5>
                                <small class="text-muted">Utilizadora desde 2025</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <div class="client-card">
                        <div class="rating">★★★★★</div>
                        <p class="fst-italic mb-4">"Como gestor de uma equipa remota, o TelePomba tornou-se essencial para o nosso trabalho diário. Os grupos e a partilha de ficheiros são excelentes!"</p>
                        <div class="d-flex align-items-center">
                            <img src="ficheiros/media/index/ramalho.jpg" alt="Client" class="client-img me-3" width="60" height="60">
                            <div>
                                <h5 class="mb-0">Ramalho Crispim</h5>
                                <small class="text-muted">Gestor de Projetos</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
                    <div class="client-card">
                        <div class="rating">★★★★★</div>
                        <p class="fst-italic mb-4">"Adoro a segurança que o TelePomba oferece. Sinto-me confortável a partilhar informações pessoais sabendo que estão protegidas."</p>
                        <div class="d-flex align-items-center">
                            <img src="ficheiros/media/index/ferreira.png" alt="Client" class="client-img me-3" width="60" height="60">
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
                <div class="col-md-8 text-center animate__animated animate__fadeInUp">
                    <h2 class="section-title">A Nossa Equipa</h2>
                    <p class="lead mb-5">Conheça os talentosos profissionais por trás do TelePomba.</p>
                </div>
            </div>
            
            <!-- Scrum Master (Maior) -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-6 animate__animated animate__fadeInUp">
                    <div class="card scrum-master-card text-center p-4">
                        <img src="ficheiros/media/index/thomaz.png" class="scrum-master-img mb-3" alt="Scrum Master" width="200" height="200">
                        <h3>Thomaz Cortez</h3>
                        <p class="text-primary fw-bold">Scrum Master & Fundador</p>
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
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <div class="card team-card text-center p-3">
                        <img src="ficheiros/media/index/marco.png" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Marco Neto</h5>
                        <p class="text-primary">Co-Founder & Definições de Utilizador</p>
                    </div>
                </div>
                
                <!-- Desenvolvedor 2 -->
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                    <div class="card team-card text-center p-3">
                        <img src="/api/placeholder/150/150" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Miguel Duarte</h5>
                        <p class="text-primary">Imagens</p>
                    </div>
                </div>
                
                <!-- Desenvolvedor 3 -->
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <div class="card team-card text-center p-3">
                        <img src="ficheiros/media/index/lourenco.png" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Lourenço Mestre</h5>
                        <p class="text-primary">Footer & Base de Dados</p>
                    </div>
                </div>
                
                <!-- Desenvolvedor 4 -->
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
                    <div class="card team-card text-center p-3">
                        <img src="ficheiros/media/index/martim.png" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Martim Marreiros</h5>
                        <p class="text-primary">Definições de Utilizador</p>
                    </div>
                </div>
                
                <!-- Desenvolvedor 5 -->
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
                    <div class="card team-card text-center p-3">
                        <img src="/api/placeholder/150/150" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Yathaarth</h5>
                        <p class="text-primary">Index</p>
                    </div>
                </div>
                
                <!-- Desenvolvedor 6 -->
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 0.7s">
                    <div class="card team-card text-center p-3">
                        <img src="/api/placeholder/150/150" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Gustavo Pomba</h5>
                        <p class="text-primary">Login, Registo & Logout</p>
                    </div>
                </div>
                
                <!-- Desenvolvedor 7 -->
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 0.8s">
                    <div class="card team-card text-center p-3">
                        <img src="/api/placeholder/150/150" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Gonçalo Dionísio</h5>
                        <p class="text-primary">Header e Navbar</p>
                    </div>
                </div>
                
                <!-- Desenvolvedor 8 -->
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 0.9s">
                    <div class="card team-card text-center p-3">
                        <img src="/api/placeholder/150/150" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Henrique</h5>
                        <p class="text-primary">Base de Dados</p>
                    </div>
                </div>
                
                <!-- Desenvolvedor 9 -->
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 1s">
                    <div class="card team-card text-center p-3">
                        <img src="/api/placeholder/150/150" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Rafael Costa</h5>
                        <p class="text-primary">Logotípo & Imagens</p>
                    </div>
                </div>
                
                <!-- Desenvolvedor 10 -->
                <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: 1.1s">
                    <div class="card team-card text-center p-3">
                        <img src="/api/placeholder/150/150" class="team-img mb-3" alt="Developer" width="150" height="150">
                        <h5>Nicolas Almeida</h5>
                        <p class="text-primary">Login, Registo e Logout</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-primary text-white">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-md-8 animate__animated animate__fadeIn">
                    <h2 class="mb-4">Pronto para começar?</h2>
                    <p class="lead mb-5">Junte-se à nossa comunidade hoje e comece a partilhar os seus momentos.</p>
                    <a href="/TelePomba/ficheiros/login/registo.php" class="btn btn-light btn-lg px-5 py-3 animate__animated animate__pulse animate__infinite animate__slower">Criar Conta Gratuitamente</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h4><i class="fas fa-dove me-2"></i> TelePomba</h4>
                    <p>Conecte-se com amigos, compartilhe momentos e mantenha-se próximo de quem mais importa.</p>
                </div>
                <div class="col-md-2">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Início</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Sobre</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Recursos</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Equipa</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5>Suporte</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">FAQ</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Centro de Ajuda</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Mantenha-se Conectado</h5>
                    <div class="d-flex gap-3 mb-3">
                        <a href="#" class="text-white fs-4"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-linkedin"></i></a>
                    </div>
                    <p>Subscreva a nossa newsletter para receber as últimas novidades.</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Seu email">
                        <button class="btn btn-light" type="button">Subscrever</button>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 TelePomba. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white text-decoration-none me-3">Política de Privacidade</a>
                    <a href="#" class="text-white text-decoration-none">Termos de Serviço</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animação ao Scroll
        document.addEventListener("DOMContentLoaded", function() {
            const animateElements = document.querySelectorAll('.animate__animated');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        let element = entry.target;
                        let animateClass = Array.from(element.classList).find(className => 
                            className.startsWith('animate__') && className !== 'animate__animated'
                        );
                        
                        if (animateClass && animateClass !== 'animate__infinite') {
                            element.classList.add(animateClass);
                            observer.unobserve(element);
                        }
                    }
                });
            }, { threshold: 0.1 });
            
            animateElements.forEach(element => {
                let animateClass = Array.from(element.classList).find(className => 
                    className.startsWith('animate__') && className !== 'animate__animated'
                );
                
                if (animateClass && animateClass !== 'animate__infinite') {
                    element.classList.remove(animateClass);
                    observer.observe(element);
                }
            });
        });
    </script>
</body>
</html>