<?php
session_start();
require_once "../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];
    
    try {
        $stmt = $conn->prepare("SELECT id_utilizador, palavra_passe FROM utilizadores WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user["palavra_passe"])) {
            $_SESSION["user_id"] = $user["id_utilizador"];
            echo "<p class='text-center text-green-600'>Login efetuado com sucesso!</p>";
        } else {
            echo "<p class='text-center text-red-600'>Email ou senha incorretos.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='text-center text-red-600'>Erro ao fazer login: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Senha" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>
</html>
