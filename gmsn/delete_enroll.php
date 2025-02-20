<?php
require_once("includes/config.php");

if(isset($_POST['studID'])){
    $studID = $_POST['studID'];

    try {
        $query = "DELETE FROM enrolled_student WHERE enrollID = :enrollID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':enrollID', $studID, PDO::PARAM_INT);

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
