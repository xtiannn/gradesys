<?php

require_once("includes/config.php");

if (isset($_POST['programId'])) {
    $programID = $_POST['programId'];

    try {
        // Prepare the DELETE query
        $query = "DELETE FROM programs WHERE programID = :programID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete the program.']);
        }
    } catch (PDOException $e) {
        // Check for foreign key constraint violation
        if ($e->getCode() == '23000') { // 23000 is the SQLSTATE code for integrity constraint violation
            echo json_encode(['status' => 'error', 'message' => 'This program cannot be deleted as it is referenced by other records. Please delete any associated subjects first, then try again.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Program ID not provided.']);
}

?>
