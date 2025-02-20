<?php
require_once("includes/config.php");

if (isset($_POST['curriculumID']) && isset($_POST['deptID'])) {
    $currID = $_POST['curriculumID'];
    $deptID = $_POST['deptID']; // Correct assignment for deptID

    if ($deptID == 2) {
        // Handle case when deptID == 2
        $delQuery1 = "DELETE FROM subject_grade_levels WHERE curriculumID = :curriculumID";
        $stmtDel1 = $conn->prepare($delQuery1);
        $stmtDel1->bindParam(':curriculumID', $currID, PDO::PARAM_INT);

        $query = "UPDATE curriculum SET typeID = null, gradelvlID = null, semID = null WHERE curriculumID = :curriculumID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':curriculumID', $currID, PDO::PARAM_INT);

        if ($stmt->execute() && $stmtDel1->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete curriculum']);
        }
    } else {
        // Handle case when deptID != 2
        $query = "UPDATE curriculum SET typeID = null, gradelvlID = null, semID = null WHERE curriculumID = :curriculumID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':curriculumID', $currID, PDO::PARAM_INT);

        $delQuery = "DELETE FROM curriculum_prerequisites WHERE curriculumID = :curriculumID";
        $stmtDel = $conn->prepare($delQuery);
        $stmtDel->bindParam(':curriculumID', $currID, PDO::PARAM_INT);

        if ($stmt->execute() && $stmtDel->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete curriculum']);
        }
    }
} elseif (isset($_POST['curriculumID'])) {
    // If only curriculumID is provided, handle this case
    $currID = $_POST['curriculumID'];

    $query = "UPDATE curriculum SET typeID = null, gradelvlID = null, semID = null WHERE curriculumID = :curriculumID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':curriculumID', $currID, PDO::PARAM_INT);

    $delQuery = "DELETE FROM curriculum_prerequisites WHERE curriculumID = :curriculumID";
    $stmtDel = $conn->prepare($delQuery);
    $stmtDel->bindParam(':curriculumID', $currID, PDO::PARAM_INT);

    if ($stmt->execute() && $stmtDel->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete curriculum']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'curriculumID or deptID not provided']);
}
?>
