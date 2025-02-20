<?php
require_once "../includes/config.php";

$sql = "SELECT ayName, semID FROM academic_year";
$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

$activeAY = $result['ayName'];
$activeSem = $result['semID'];

?>