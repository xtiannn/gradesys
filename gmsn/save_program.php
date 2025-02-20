<?php 

require_once("includes/config.php");

if (isset($_POST['saveProgramBtn'])) {
    $programCode = $_POST['txtProgramCode'];
    $programName = ucwords(strtolower($_POST['txtProgramName']));

    try {
        // Check if program code already exists
        $checkQuery = "SELECT COUNT(*) FROM programs WHERE programcode = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$programCode]);
        $existingProgramCount = $checkStmt->fetchColumn();

        if ($existingProgramCount > 0) {
            // If duplicate entry found, redirect with status=duplicate
            header('Location: programs.php?operation=insert&status=duplicate');
            exit();
        }

        // Proceed with insertion if no duplicates
        $query = "INSERT INTO programs (programcode, programname, isActive) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$programCode, $programName]);

        if ($stmt) {
            header('Location: programs.php?operation=insert&status=success');
        } else {
            header('Location: programs.php?operation=insert&status=error');
        }
    } catch (PDOException $e) {
        header('Location: programs.php?operation=insert&status=error');
    }

}else if (isset($_POST['updateProgramBtn'])) {
    $programCode = $_POST['txtProgramCode'];
    $programName = ucwords(strtolower($_POST['txtProgramName']));
    $programID = $_POST['programID'];

    // Get the 'status' value from the hidden field (it will be 0 or 1)
    $status = $_POST['status'];

    try {
        // Check if program code already exists for a different program (exclude current program)
        $checkQuery = "SELECT COUNT(*) FROM programs WHERE programcode = ? AND programID != ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$programCode, $programID]);
        $existingProgramCount = $checkStmt->fetchColumn();

        if ($existingProgramCount > 0) {
            // If duplicate entry found, redirect with status=duplicate
            header('Location: programs.php?operation=update&status=duplicate');
            exit();
        }

        // Proceed with update if no duplicates
        $query = "UPDATE programs SET programcode = ?, programname = ?, isActive = ? WHERE programID = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$programCode, $programName, $status, $programID]);

        if ($stmt->rowCount() > 0) {
            header('Location: programs.php?operation=update&status=success');
        } else {
            header('Location: programs.php?operation=update&status=info');
        }
    } catch (PDOException $e) {
        header('Location: programs.php?operation=update&status=error');
    }
}

?>
