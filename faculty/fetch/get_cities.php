<?php
require_once("../includes/config.php");

if (isset($_POST['provCode'])) {
    $provCode = $_POST['provCode'];
    $query = "SELECT citymunCode, citymunDesc, id FROM refcitymun WHERE provCode = :provCode";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':provCode', $provCode, PDO::PARAM_STR);
    $stmt->execute();
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $cityArray = array();
    foreach ($cities as $city) {
        $cityArray[] = array(
            'citymunCode' => $city['citymunCode'],
            'citymunDesc' => $city['citymunDesc'],
            'id' => $city['id']
        );
    }
    echo json_encode($cityArray);
}
?>
