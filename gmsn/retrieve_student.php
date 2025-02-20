<?php

require_once("includes/config.php");

if(isset($_POST['studentId'])){
    $studentID = $_POST['studentId'];

    try {
        $query = "UPDATE students SET isActive = 1 WHERE studID = :studID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':studID', $studentID, PDO::PARAM_INT);

        if($stmt->execute()){
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to mark student as inactive']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Student ID not provided']);
}

?>
