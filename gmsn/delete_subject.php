<?php
require_once("includes/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    // Single deletion in subjects table
    if (isset($_POST['subjectId'])) {
        $subjectID = $_POST['subjectId'];

        $conn->beginTransaction();

        // Prepare delete statements
        $query0 = "DELETE FROM subject_program WHERE subjectID = :subjectID";
        $stmt0 = $conn->prepare($query0);
        $stmt0->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);

        $query1 = "DELETE FROM subjects WHERE subjectID = :subjectID";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);

        $query2 = "DELETE FROM curriculum WHERE subjectID = :subjectID";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);

        try {
            // Execute delete queries
            if ($stmt0->execute() && $stmt1->execute() && $stmt2->execute()) {
                // Commit the transaction
                $conn->commit();
                echo json_encode(['status' => 'success']);
            } else {
                // Rollback the transaction
                $conn->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete subject']);
            }
        } catch (PDOException $e) {
        // Check for foreign key constraint violation
            if ($e->getCode() == '23000') { 
                echo json_encode(['status' => 'error', 'message' => 'The program cannot be deleted because it is currently referenced in other records.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
            }
        }

    // Deletion of enrolled students
    } elseif (isset($_POST['enrollID'])) {
        $enrollID = $_POST['enrollID'];

        $query1 = "DELETE FROM section_students WHERE enrollID = :enrollID";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bindParam(':enrollID', $enrollID, PDO::PARAM_INT);

        $query2 = "DELETE FROM student_grades WHERE enrollID = :enrollID";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bindParam(':enrollID', $enrollID, PDO::PARAM_INT);

        try {
            if ($stmt1->execute() && $stmt2->execute()) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete enrolled students']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'No valid ID provided']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
