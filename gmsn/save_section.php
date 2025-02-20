<?php 

require_once("includes/config.php");

if (isset($_POST['saveSectionsBtn'])) {
    $gradelvl = $_POST['selgradelvl'] ?? null;
    $section = ucwords(strtolower(trim($_POST['txtsection']))) ?? null;
    $sem = $_POST['selSem'] ?? null;
    $ay = $_POST['selAY'] ?? null;
    $ayName = $_POST['txtAyName'] ?? null;
    $prog = $_POST['selProg'] ?? null;
    $adviser = $_POST['selAdv'] ?? null;
    $deptID = $_POST['deptID'] ?? null;

    // Always redirect to section_builder.php
    $redirectUrl = 'section_builder.php';

    if (empty($section) || empty($gradelvl)) {
        echo '<script>alert("Both Grade Level and Section Name are required. Please fill out the missing information.");</script>';
    } else {
        try {
            // Check if the section name already exists for the given academic year and semester

            if($deptID == 3){
                $checkQuery = "SELECT COUNT(*) FROM sections WHERE secName = :section AND ayName = :ayName AND semID = :semID";
                $checkStmt = $conn->prepare($checkQuery);
                $checkStmt->bindParam(':section', $section);
                $checkStmt->bindParam(':ayName', $ayName);
                $checkStmt->bindParam(':semID', $sem);
            }else{
                $checkQuery = "SELECT COUNT(*) FROM sections WHERE secName = :section AND ayName = :ayName";
                $checkStmt = $conn->prepare($checkQuery);
                $checkStmt->bindParam(':section', $section);
                $checkStmt->bindParam(':ayName', $ayName);
            }
            $checkStmt->execute();
            $existingSectionCount = $checkStmt->fetchColumn();

            // If a section with the same name already exists, show an error
            if ($existingSectionCount > 0) {
                header('Location: ' . $redirectUrl . '?status=duplicate');
                exit();
            } else {
                // Proceed with inserting the new section
                $conn->beginTransaction();

                // Query to insert into sections
                $query = "INSERT INTO sections (ayID, ayName, semID, gradelvlID, secName, programID, facultyID, deptID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute([$ay, $ayName, $sem, $gradelvl, $section, $prog, $adviser, $deptID]);

                // Commit transaction
                $conn->commit();

                if ($stmt->rowCount() > 0) {
                    header("Location: $redirectUrl?status=success");
                } else {
                    header('Location: ' . $redirectUrl . '?status=error&message=Failed to save section');
                }
            }
        } catch (PDOException $e) {
            // Rollback transaction on error
            $conn->rollBack();
            header('Location: ' . $redirectUrl . '?status=error&message=Database error');
        }
        exit();
    }
}




else if (isset($_POST['updateSectionsBtn'])) {
    $sectionID = ucwords(strtolower(trim($_POST['sectionID']))) ?? null;
    $gradelvl = $_POST['selgradelvl'] ?? null;
    $section = $_POST['txtsection'] ?? null;
    $sem = $_POST['selSem'] ?? null;
    $ay = $_POST['selAY'] ?? null;
    $prog = $_POST['selProg'] ?? null;
    $adviser = $_POST['selAdv'] ?? null;
    $ayName = $_POST['updateSelAy'] ?? null;
    $deptID = $_POST['deptID'] ?? null;

    // Always redirect to section_builder.php
    $redirectUrl = 'section_builder.php';

    if (empty($section)) {
        header("Location: section_builder.php?updstatus=empty");
        exit();
    } else {
        // Check if the section name already exists for the given academic year and semester
        $checkQuery = "SELECT COUNT(*) FROM sections 
                       WHERE secName = :section AND ayName = :ayName AND secID != :secID" . 
                       ($deptID == 3 ? " AND semID = :semID" : "");
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':section', $section);
        $checkStmt->bindParam(':secID', $secID);
        $checkStmt->bindParam(':ayName', $ayName);
        if ($deptID == 3) {
            $checkStmt->bindParam(':semID', $sem);
        }
        $checkStmt->execute();
        $existingSectionCount = $checkStmt->fetchColumn();

        // If a section with the same name already exists, show an error
        if ($existingSectionCount > 0 && $sectionID != null) {
            header('Location: ' . $redirectUrl . '?updstatus=duplicate');
            exit();
        } else {
            try {
                // Update logic with or without an adviser
                $query = $adviser === null ?
                    "UPDATE sections SET secName = ? WHERE secID = ?" :
                    "UPDATE sections SET secName = ?, facultyID = ? WHERE secID = ?";
                $stmt = $conn->prepare($query);
                $params = $adviser === null ?
                    [$section, $sectionID] :
                    [$section, $adviser, $sectionID];
                $stmt->execute($params);

                // Handle different outcomes
                if ($stmt->rowCount() > 0) {
                    header("Location: $redirectUrl?updstatus=success");
                    exit();
                } else {
                    header("Location: $redirectUrl?updstatus=no-changes");
                    exit();
                }
            } catch (PDOException $e) {
                // Log the error for debugging purposes (do not expose to users in production)
                error_log("Database error: " . $e->getMessage());
                header('Location: ' . $redirectUrl . '?updstatus=duplication');
                exit();
            }
        }
    }
}



?>
