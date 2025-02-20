<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enrollBtn'])) {
    if (!empty($_POST['selStud'])) {
        if (isset($_POST['selSem']) && isset($_POST['selAY'])) {

            $secID = $_POST['secID'] ?? '';
            $programID = $_POST['programID'] ?? '';
            $subjectID = $_POST['subjectID'] ?? '';
            $gradelvlID = $_POST['gradelvlID'] ?? '';
            $facultyID = $_POST['facultyID'] ?? '';
            $facultyName = $_POST['facultyName'] ?? '';
            $secName = $_POST['secName'] ?? '';
            $subjectName = $_POST['subjectName'] ?? '';
            $selAY = $_POST['selAY'] ?? '';
            $semID = $_POST['semID'] ?? '';

            
            try {
                require_once("../includes/config.php");
                $conn->beginTransaction();
            
                $check_sql = "SELECT COUNT(*) FROM section_students WHERE studID = :studID AND semID = :selSem AND ayID = :selAY AND subjectID = :subjectID";
                $check_stmt = $conn->prepare($check_sql);
                
                foreach ($_POST['selStud'] as $studID) {
                    $check_stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                    $check_stmt->bindParam(':selSem', $_POST['selSem'], PDO::PARAM_INT);
                    $check_stmt->bindParam(':selAY', $_POST['selAY'], PDO::PARAM_STR);
                    $check_stmt->bindParam(':subjectID', $_POST['subjectID'], PDO::PARAM_STR);
                    $check_stmt->execute();
                    $count = $check_stmt->fetchColumn();
            
                    if ($count > 0) {
                        header("Location: ../student.php?secID=$secID&secName=$secName&subjectName=$subjectName&programID=$programID&subjectID=$subjectID&gradelvlID=$gradelvlID&ayID=$selAY&facultyID=$facultyID&facultyName=$facultyName");
                        exit();
                    }
                }
            
                $sql = "INSERT INTO section_students (studID, semID, ayID, subjectID, programID, gradelvlID, secID, facultyID)
                VALUES (:studID, :selSem, :selAY, :subjectID, :programID, :gradelvlID, :secID, :facultyID)";
                $stmt = $conn->prepare($sql);
            
                foreach ($_POST['selStud'] as $studID) {
                    $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                    $stmt->bindParam(':selSem', $_POST['selSem'], PDO::PARAM_INT);
                    $stmt->bindParam(':selAY', $_POST['selAY'], PDO::PARAM_STR);
                    $stmt->bindParam(':subjectID', $_POST['subjectID'], PDO::PARAM_STR);
                    $stmt->bindParam(':programID', $_POST['programID'], PDO::PARAM_STR);
                    $stmt->bindParam(':gradelvlID', $_POST['gradelvlID'], PDO::PARAM_STR);
                    $stmt->bindParam(':secID', $_POST['secID'], PDO::PARAM_STR);
                    $stmt->bindParam(':facultyID', $_POST['facultyID'], PDO::PARAM_STR);
                    $stmt->execute();
                }
            
                $conn->commit();
            
                header("Location: ../student.php?secID=$secID&secName=$secName&subjectName=$subjectName&programID=$programID&subjectID=$subjectID&gradelvlID=$gradelvlID&ayID=$selAY&facultyID=$facultyID&facultyName=$facultyName&semID=$semID");
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();
            
                error_log("Database error: " . $e->getMessage());
            
                header("Location: ../student.php?error=database_error");
                exit();
            } finally {
                $conn = null;
            }
            
        } else {
            header("Location: ../student.php?error=missing_parameters");
            exit();
        }
    } else {
        header("Location: ../student.php?error=no_students_selected");
        exit();
    }
} 
else {
    header("Location: ../student.php?error=invalid_request");
    exit();
}


?>
