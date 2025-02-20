<?php
require_once("includes/config.php");

if (isset($_POST['secID'])) {
    $sectionID = $_POST['secID'];

    try {
        $query = "UPDATE sections SET isActive = 0 WHERE secID = :secID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':secID', $sectionID, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No section found with the specified ID or related students still exist.']);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot move this section as there are students enrolled in it.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
} elseif (isset($_POST['archivedSecID'])) {
    $archivedSectionID = $_POST['archivedSecID']; // Use the correct variable name here

    try {
        // Query to restore the section
        $query = "UPDATE sections SET isActive = 1 WHERE secID = :secID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':secID', $archivedSectionID, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No section found with the specified ID or related students still exist.']);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot restore this section']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
?>
