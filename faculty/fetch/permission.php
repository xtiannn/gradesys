<?php 
  require_once("../includes/config.php");

$sql = "SELECT _first, _second FROM gradepermission"; 
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$firstSwitchValue = $result['_first'];
$secondSwitchValue = $result['_second'];
?>