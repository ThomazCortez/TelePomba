<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telepomba</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #2c3e50, #1c2833);
            color: white;
            text-align: center;
            overflow-x: hidden;
        }

        header {
            background: linear-gradient(90deg, #1abc9c, #16a085);
            color: white;
            padding: 40px 20px;
            font-size: 36px;
            font-weight: bold;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease-in-out;
        }

        main {
            margin-top: 50px;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            animation: fadeInUp 1s ease-in-out;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.4);
        }

        h1, h2 {
            color: #1abc9c;
            margin-bottom: 20px;
            font-weight: 700;
        }

        p {
            font-size: 18px;
            line-height: 1.8;
            color: #ecf0f1;
        }

        button {
            background: linear-gradient(90deg, #1abc9c, #16a085);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 8px;
            transition: 0.3s;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        button:hover {
            background: linear-gradient(90deg, #16a085, #1abc9c);
            transform: scale(1.05);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            background: rgba(236, 240, 241, 0.1);
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            color: #ecf0f1;
            font-size: 16px;
            transition: 0.3s;
        }

        ul li:hover {
            background: rgba(236, 240, 241, 0.2);
            transform: translateX(10px);
        }

        /* Animations */
        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                font-size: 28px;
                padding: 20px;
            }

            .container {
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 24px;
            }

            p {
                font-size: 16px;
            }

            button {
                padding: 12px 24px;
                font-size: 16px;
            }

            ul li {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <header>Telepomba</header>
    <main>
        <section class="container">
            <h1>Bem-vindo ao Telepomba!</h1>
            <p>O seu portal para tudo sobre pombas e telecomunicações.</p>
            <button>Saiba Mais</button>
        </section>
        <section class="container">
            <h2>Sobre Nós</h2>
            <p>A Telepomba é uma iniciativa inovadora que une o mundo das telecomunicações com a elegância das pombas.</p>
        </section>
        <section class="container">
            <h2>Equipa</h2>
            <p>Nossa equipa dedicada trabalha incansavelmente para trazer as melhores informações acerca de pombas e tecnologias.</p>
            <ul>
                <li><b>Thomaz</b> - Scrum Master e Desenvolvedor dos CSS deste site</li>
                <li><b>Gustavo e Nicolas</b> - Login, Registo e Implementação da Base de Dados</li>
                <li><b>Henrique</b> - Base de Dados</li>
                <li><b>Yathaarth e Miguel</b> - Criação da Página Inicial</li>
                <li><b>Marco e Martim</b> - Definições de Utilizador</li>
                <li><b>Miguel</b> - Footer</li>
                <li><b>Gonçalo</b> - Navbar, Header e Dropdown</li>
                <li><b>Todos</b> - Responsividade</li>
                <li><b>Todos os desenvolvedores</b> - Definição de utilizador como admin, criação de mensagens privadas e funcionalidades de gestão de utilizadores</li>
            </ul>
        </section>
        <section class="container">
            <h2>Contato</h2>
            <p>Entre em contato conosco através do e-mail: <b>contato@telepomba.com</b></p>
        </section>
    </main>
</body>
</html>