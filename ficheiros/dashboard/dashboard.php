<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['id_utilizador'])) {
    header('Location: /TelePomba/ficheiros/login/login.php');
    exit();
}
?>
<a href="perfil.php" class="login-link">teste</a>