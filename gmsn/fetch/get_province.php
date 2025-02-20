<?php
require_once("../includes/config.php");

if (isset($_POST['regCode'])) {
    $regCode = $_POST['regCode'];
    $query = "SELECT provCode, provDesc, id FROM refprovince WHERE regCode = :regCode";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':regCode', $regCode, PDO::PARAM_STR);
    $stmt->execute();
    $provs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $provArray = array();
    foreach ($provs as $prov) {
        $provArray[] = array(
            'provCode' => $prov['provCode'],
            'provDesc' => $prov['provDesc'],
            'id' => $prov['id']
        );
    }
    echo json_encode($provArray);
}
?>
