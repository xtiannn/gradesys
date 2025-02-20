<?php 

require_once("includes/config.php");

if(isset($_POST['ayId'])){
    $ayID = $_POST['ayId'];

    $query = "DELETE FROM academic_year WHERE ayID = :ayID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ayID', $ayID, PDO::PARAM_INT);

    if($stmt->execute()){
        echo json_encode(['status' => 'success']);
    }
    else{
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete the session']);
    }
}
else{
    echo json_encode(['status' => 'error', 'message' => 'Session ID not provided']);
}

?>
