<?php
    require_once "../includes/config.php";

    $sqlGP = "SELECT * FROM gradepermission";
    $stmtGP = $conn->prepare($sqlGP);
    $stmtGP->execute();

    $resultGP = $stmtGP->fetch(PDO::FETCH_ASSOC);

    $first = $resultGP['_first'];
    $second = $resultGP['_second'];
?>