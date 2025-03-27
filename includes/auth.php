<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
}

function loginUser($userId, $username, $profileImage) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['profile_image'] = $profileImage;
}

function logoutUser() {
    session_unset();
    session_destroy();
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Add this to your existing auth.php
function checkActiveSession() {
    if (isLoggedIn()) {
        // Verify the session hasn't been invalidated
        $sessionFile = session_save_path() . '/sess_' . session_id();
        if (!file_exists($sessionFile)) {
            logoutUser();
            header("Location: index.php");
            exit();
        }
    }
}
?>