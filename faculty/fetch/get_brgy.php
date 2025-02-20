<?php
require_once("../includes/config.php");

if (isset($_POST['citymunCode'])) {
    $citymunCode = $_POST['citymunCode'];
    $query = "SELECT brgyCode, brgyDesc, id FROM refbrgy WHERE citymunCode = :citymunCode";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':citymunCode', $citymunCode, PDO::PARAM_STR);
    $stmt->execute();
    $brgys = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $brgyArray = array();
    foreach ($brgys as $brgy) {
        $brgyArray[] = array(
            'brgyCode' => $brgy['brgyCode'],
            'brgyDesc' => $brgy['brgyDesc'],
            'id' => $brgy['id']
        );
    }
    echo json_encode($brgyArray);
}
?>
