<?php

require_once("includes/config.php");

if (isset($_POST['facultyID'])) {
    $facultyID = $_POST['facultyID'];

    $conn->beginTransaction();

    try {
        $query1 = "DELETE FROM faculty WHERE facultyID = :facultyID";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
        $result1 = $stmt1->execute();

        $query2 = "DELETE FROM users WHERE facultyID = :facultyID";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
        $result2 = $stmt2->execute();

        if ($result1 && $result2) {
            $conn->commit();
            echo json_encode(['status' => 'success']);
        } else {
            $conn->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Failed to Delete Faculty']);
        }
    } catch (Exception $e) {
        $conn->rollBack();

        // Check if the error is due to a foreign key constraint violation
        if ($e->getCode() == '23000') { // Error code for foreign key constraint violation in MySQL
            echo json_encode(['status' => 'error', 'message' => 'The faculty cannot be deleted at this time as they are associated with existing records. Please resolve the dependencies before attempting to delete.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

}elseif (isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    $conn->beginTransaction();

    try {
        $query1 = "UPDATE faculty SET isActive = 0 WHERE facultyID = :facultyID";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bindParam(':facultyID', $uid, PDO::PARAM_INT); 
        $result1 = $stmt1->execute();

        $query2 = "UPDATE users SET isActive = 0 WHERE uid = :uid";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bindParam(':uid', $uid, PDO::PARAM_INT);
        $result2 = $stmt2->execute();

        if ($result2 && $result1) {
            $conn->commit();
            echo json_encode(['status' => 'success']);
        } else {
            $conn->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete faculty.']);
        }
    } catch (PDOException $e) {
        $conn->rollBack();

        // Check if the error is due to a foreign key constraint violation
        if ($e->getCode() == '23000') { // Error code for foreign key constraint violation in MySQL
            echo json_encode(['status' => 'error', 'message' => 'The faculty cannot be deleted at this time as they are associated with existing records. Please resolve the dependencies before attempting to delete.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'facultyID not provided']);
}

?>
