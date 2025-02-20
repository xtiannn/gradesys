<?php
// Start the session if not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: ../logout.php');
    exit();
}
?>
