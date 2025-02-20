<?php
require_once("includes/config.php");

if(isset($_POST['secID'])){
    $sectionID = $_POST['secID'];

    try {
        // First query to delete from 'sections' table
        $query = "DELETE FROM sections WHERE secID = :secID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':secID', $sectionID, PDO::PARAM_INT);
        $stmt->execute();

        // Second query to delete from 'section_students' table
        $query2 = "DELETE FROM section_students WHERE secID = :secID";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bindParam(':secID', $sectionID, PDO::PARAM_INT);
        $stmt2->execute();


        // Check if both queries were successful
        if($stmt->rowCount() > 0){
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No section found with the specified ID or related students still exist.']);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot delete this section as there are students enrolled in it.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
?>
