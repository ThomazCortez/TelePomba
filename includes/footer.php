<!-- Footer CSS -->
<style>
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

<!-- Footer HTML -->
<footer class="animate__animated animate__fadeInUp">
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
        <p>&copy; <?= date('Y') ?> TelePomba - Todos os direitos reservados.</p>
        <div class="legal-links">
            <a href="/TelePomba/utilizador/politicaprivacidade.php">Política de Privacidade</a>
            <a href="/TelePomba/utilizador/termosservico.php">Termos de Serviço</a>
        </div>
    </div>
</footer>