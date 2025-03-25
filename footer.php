<!-- Adicione isto antes do </body> -->
<footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Telepomba</h3>
                <p>Conectando pombas e telecomunicações desde 2025</p>
            </div>
            
            <div class="footer-section">
                <h4>Contacto</h4>
                <p>📧 contato@telepomba.com</p>
                <p>📞 (+351)939658201</p>
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
                <a href="#">Política de Privacidade</a>
                <a href="#">Termos de Serviço</a>
            </div>
        </div>
    </footer>

<style>
html, body {
    height: 100%;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

main {
    flex: 1;
    padding-bottom: 50px;
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
</style>

<!-- Adicione isto no <head> para os ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">