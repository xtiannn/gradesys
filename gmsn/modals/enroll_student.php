<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['promoteBtn'])) {
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
            $deptID = $_POST['deptID'] ?? '';

            
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
                    $stmt->bindParam(':selAY', $_POST['selAY'], PDO::PARAM_INT);
                    $stmt->bindParam(':subjectID', $_POST['subjectID'], PDO::PARAM_INT);
                    $stmt->bindParam(':programID', $_POST['programID'], PDO::PARAM_INT);
                    $stmt->bindParam(':gradelvlID', $_POST['gradelvlID'], PDO::PARAM_INT);
                    $stmt->bindParam(':secID', $_POST['secID'], PDO::PARAM_INT);
                    $stmt->bindParam(':facultyID', $_POST['facultyID'], PDO::PARAM_INT);
                    $stmt->execute();
                }
            
                $conn->commit();
            
                if($deptID == 3){
                    header("Location: ../student.php?secID=$secID&secName=$secName&subjectName=$subjectName&programID=$programID&subjectID=$subjectID&gradelvlID=$gradelvlID&ayID=$selAY&facultyID=$facultyID&facultyName=$facultyName&semID=$semID&deptID=$deptID");
                }elseif($deptID == 2){
                    header("Location: ../student.php?secID=$secID&secName=$secName&subjectName=$subjectName&subjectID=$subjectID&gradelvlID=$gradelvlID&ayID=$selAY&facultyID=$facultyID&facultyName=$facultyName&deptID=$deptID");
                }else{
                    header("Location: ../student.php?secID=$secID&secName=$secName&subjectName=$subjectName&programID=$programID&subjectID=$subjectID&gradelvlID=$gradelvlID&ayID=$selAY&facultyID=$facultyID&facultyName=$facultyName&semID=$semID");
                }
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();            
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
