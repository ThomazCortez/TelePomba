<?php
// The SMTP password lives in config.local.php, which is not in Git.
// Copy config.local.example.php to config.local.php and fill it in.
$EMAIL_LOCAL = file_exists(__DIR__ . '/config.local.php') ? require __DIR__ . '/config.local.php' : [];

$DB_CONFIG = [
    'host' => 'localhost',
    'database' => 'telepomba',
    'username' => 'root',
    'password' => ''
];

$EMAIL_CONFIG = [
    'smtp_host' => 'smtp.gmail.com',
    'smtp_username' => 'telepombadev@gmail.com', 
    'smtp_password' => $EMAIL_LOCAL['smtp_password'] ?? '',
    'smtp_secure' => 'tls',
    'smtp_port' => 587
];