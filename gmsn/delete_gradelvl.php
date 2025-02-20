<?php

require_once("includes/config.php");

if (isset($_POST['gradelvlId'])) {
    $gradeLevelID = $_POST['gradelvlId'];

    try {
        $query = "DELETE FROM grade_level WHERE gradelvlID = :gradelvlID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':gradelvlID', $gradeLevelID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'The request to delete the grade level could not be processed.']);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') { 
            echo json_encode(['status' => 'error', 'message' => 'The grade level cannot be deleted because it is referenced in other records.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No grade level ID was provided in the request.']);
}

?>
