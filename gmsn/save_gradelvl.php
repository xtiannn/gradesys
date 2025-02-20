<?php 

require_once("includes/config.php");

if (isset($_POST['saveGradelvlBtn'])) {
    $gradelvlCode = ucwords(strtolower(trim($_POST['txtGradelvlCode'])));
    $gradelvlName = ucwords(strtolower(trim($_POST['txtGradelvlName'])));
            
    try {
        // Check if program code and name already exist
        $checkQuery = "SELECT COUNT(*) FROM grade_level WHERE gradelvlcode = ? OR gradelvl = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$gradelvlCode, $gradelvlName]);
        $existingLevelCount = $checkStmt->fetchColumn();

        if ($existingLevelCount > 0) {
            // If duplicate entry found, redirect with status=duplicate
            header('Location: grade_level.php?status=duplicate');
            exit();
        }
        
        // Insert the grade level if no duplicates found
        $query = "INSERT INTO grade_level (gradelvlcode, gradelvl, isActive) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($query);

        if ($stmt->execute([$gradelvlCode, $gradelvlName])) {
            header("Location: grade_level.php?status=success");
        } else {
            header("Location: grade_level.php?status=failed");
        }
    } catch (PDOException $e) {
        echo '<script> alert("Error: ' . $e->getMessage() . '");</script>';
    }
    exit();
} elseif (isset($_POST['updateGradelvlBtn'])) {
    $gradelvlID = $_POST['gradelvlID'];
    $status = $_POST['status'];
    $gradelvlCode = ucwords(strtolower(trim($_POST['txtGradelvlCode'])));
    $gradelvlName = ucwords(strtolower(trim($_POST['txtGradelvlName'])));

    try {
        // Check if the updated code or name already exists (excluding current ID)
        $checkQuery = "SELECT COUNT(*) FROM grade_level WHERE (gradelvlcode = ? OR gradelvl = ?) AND gradelvlID != ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$gradelvlCode, $gradelvlName, $gradelvlID]);
        $existingLevelCount = $checkStmt->fetchColumn();

        if ($existingLevelCount > 0) {
            // If duplicate entry found, redirect with status=duplicate
            header("Location: grade_level.php?status=duplicate");
            exit();
        }

        // Update the grade level if no duplicates found
        $query = "UPDATE grade_level SET isActive = ?, gradelvlcode = ?, gradelvl = ? WHERE gradelvlID = ?";
        $stmt = $conn->prepare($query);

        if ($stmt->execute([$status, $gradelvlCode, $gradelvlName, $gradelvlID])) {
            if ($stmt->rowCount() > 0) {
                header("Location: grade_level.php?status=updated");
            } else {
                header(header: "Location: grade_level.php?status=nochanges");
            }
        } else {
            header("Location: grade_level.php?status=failed");
        }
    } catch (PDOException $e) {
        echo '<script> alert("Error: ' . $e->getMessage() . '");</script>';
    }
    exit();
}
?>
