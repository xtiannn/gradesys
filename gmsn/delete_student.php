<?php

require_once("includes/config.php");

if (isset($_POST['studentId'])) {
    $studentID = $_POST['studentId'];

    try {
        // Check if the student has records in section_students or student_grades
        $checkQuery = "
            SELECT 
                (SELECT COUNT(*) FROM section_students WHERE studID = :studID) AS section_count
        ";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':studID', $studentID, PDO::PARAM_INT);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($result['section_count'] > 0) {
            // If records exist, return an error message
            echo json_encode([
                'status' => 'error',
                'message' => 'Cannot delete the student as they have existing records in sections or grades.'
            ]);
            exit; // Stop further execution
        }

        // Proceed with the update if no records exist
        $query = "UPDATE students SET isActive = 0 WHERE studID = :studID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':studID', $studentID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to mark student as inactive']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

else if(isset($_POST['enrollID'])){
    $enrollID = $_POST['enrollID'];
    $studID = $_POST['studID'];

    try {
        $query = "DELETE FROM section_students WHERE enrollID = :enrollID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':enrollID', $enrollID, PDO::PARAM_INT);

        $query2 = "DELETE FROM section_students WHERE studID = :studID";
        $stmt2 = $conn->prepare($query);
        $stmt2->bindParam(':studID', $studID, PDO::PARAM_INT);

        if($stmt->execute()){ 
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to mark student as inactive']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

elseif(isset($_POST['studID']) && isset($_POST['subjectID'])) {
    $enrollID = $_POST['enrollID'];
    $studID = $_POST['studID'];
    $subjectID = $_POST['subjectID'];

    try {
        $query = "DELETE FROM section_students WHERE enrollID = :enrollID AND studID = :studID AND subjectID = :subjectID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':enrollID', $enrollID, PDO::PARAM_INT);
        $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
        $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);

        if($stmt->execute()) { 
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete the student']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

?>
