<?php
require_once '../includes/auth.php';

// Notify Socket.io server about logout
$userId = getCurrentUserId();
?>
<script>
    // Notify Socket.io server before redirecting
    if (typeof socket !== 'undefined') {
        socket.emit('userLogout', <?= $userId ?>);
    }
    // Redirect after a short delay to ensure the message is sent
    setTimeout(() => {
        window.location.href = '../includes/logout_process.php';
    }, 100);
</script>