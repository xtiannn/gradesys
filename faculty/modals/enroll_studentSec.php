<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enrollSecBtn'])) {
    if (!empty($_POST['selStud'])) {
        if (isset($_POST['selSem']) && isset($_POST['selAY'])) {

            $selSem = isset($_POST['selSem']) ? trim($_POST['selSem']) : '';
            $secID = $_POST['secID'] ?? '';
            $programID = $_POST['programID'] ?? '';
            $gradelvlID = $_POST['gradelvlID'] ?? '';
            $secName = $_POST['secName'] ?? '';

          
            try {
                require_once("../includes/config.php");
                $conn->beginTransaction();
            
                $sql = "INSERT INTO section_students (studID, semID, ayID, programID, gradelvlID, secID)
                VALUES (:studID, :selSem, :selAY, :programID, :gradelvlID, :secID)";
                $stmt = $conn->prepare($sql);
            
                foreach ($_POST['selStud'] as $studID) {
                    $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                    $stmt->bindParam(':selSem', $_POST['selSem'], PDO::PARAM_INT);
                    $stmt->bindParam(':selAY', $_POST['selAY'], PDO::PARAM_STR);
                    $stmt->bindParam(':programID', $_POST['programID'], PDO::PARAM_STR);
                    $stmt->bindParam(':gradelvlID', $_POST['gradelvlID'], PDO::PARAM_STR);
                    $stmt->bindParam(':secID', $_POST['secID'], PDO::PARAM_STR);
                    $stmt->execute();
                }
            
                $conn->commit();
            
                header("Location: ../enrolled_students.php?semID=$selSem&secName=$secName&secID=$secID&programID=$programID&gradelvlID=$gradelvlID");
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();
            
                error_log("Database error: " . $e->getMessage());
            
                header("Location: ../enrolled_students.php?error=database_error");
                exit();
            } finally {
                $conn = null;
            }
            
        } else {
            header("Location: ../enrolled_students.php?error=missing_parameters");
            exit();
        }
    } else {
        header("Location: ../enrolled_students.php?error=no_students_selected");
        exit();
    }
} else {
    header("Location: ../enrolled_students.php?error=invalid_request");
    exit();
}

?>