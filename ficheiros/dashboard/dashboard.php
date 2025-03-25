<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['id_utilizador'])) {
    header('Location: /TelePomba/ficheiros/login/login.php');
    exit();
}
?>