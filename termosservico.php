<!-- termosservico.php -->
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Serviço - Telepomba</title>
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
                   <!-- <li class="nav-item">
                        <a class="nav-link" href="/TelePomba/index.php#about">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/TelePomba/index.php#features">Recursos</a>
                    </li>-->
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
        <h1>Termos de Serviço</h1>

        <h2>1. Aceitação dos Termos</h2>
        <p>Ao utilizar os nossos serviços, o utilizador aceita estes termos e a nossa Política de Privacidade. Caso não concorde, deverá abster-se de utilizar os nossos serviços.</p>

        <h2>2. Responsabilidades do Utilizador</h2>
        <p>O utilizador compromete-se a não utilizar os nossos serviços para atividades ilegais e a manter a confidencialidade da sua conta. Qualquer utilização indevida resultará na cessação imediata do serviço.</p>

        <h2>3. Propriedade Intelectual</h2>
        <p>Todos os direitos sobre o conteúdo e software da Telepomba estão reservados. É proibida a reprodução não autorizada de qualquer elemento da plataforma.</p>

        <h2>4. Limitação de Responsabilidade</h2>
        <p>Não nos responsabilizamos por danos indiretos ou consequenciais decorrentes da utilização dos serviços. A nossa responsabilidade máxima estará limitada ao valor pago pelo serviço.</p>

        <h2>5. Legislação Aplicável</h2>
        <p>Estes termos são regidos pela legislação portuguesa. Qualquer litígio será resolvido nos tribunais judiciais de Lisboa.</p>

        <h2>6. Alterações</h2>
        <p>Reservamo-nos o direito de alterar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após a sua publicação na plataforma.</p>
    </main>


    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS com Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>