<?php
require_once 'auth.php';

// Destroy the session
logoutUser();

// Redirect to login page
header("Location: ../index.php");
exit();
?>