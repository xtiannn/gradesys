<?php
session_start();

$_SESSION = array();
session_destroy();

header("Location: /cap/index.php");
exit;
?>
