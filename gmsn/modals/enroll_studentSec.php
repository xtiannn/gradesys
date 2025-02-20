<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enrollSecBtn'])) {
    if (!empty($_POST['selStud'])) {
        if (isset($_POST['selSem']) && isset($_POST['selAY'])) {

            $selSem = isset($_POST['selSem']) ? trim($_POST['selSem']) : '';
            $secID = $_POST['secID'] ?? NULL;
            $programID = $_POST['programID'] ?? NULL;
            $gradelvlID = $_POST['gradelvlID'] ?? NULL;
            $secName = $_POST['secName'] ?? NULL;
            $facultyID = $_POST['facultyID'] ?? NULL;
            $deptID = $_POST['deptID'] ?? NULL;
            $ayName = $_POST['ayName'] ?? '';
            $ayID = $_POST['ayID'] ?? NULL;
            $isIrreg = $_POST['isIrreg'] ?? NULL;
            

          
            try {
                require_once("../includes/config.php");
                $conn->beginTransaction();
            
                if($deptID == 3){
                    $sql = "INSERT INTO section_students (adviserID, studID, semID, ayID, programID, gradelvlID, secID, ayName)
                    VALUES (:adviserID, :studID, :selSem, :selAY, :programID, :gradelvlID, :secID, :ayName)";
                    $stmt = $conn->prepare($sql);
                
                    foreach ($_POST['selStud'] as $studID) {
                        $stmt->bindParam(':adviserID', $facultyID, PDO::PARAM_INT);
                        $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                        $stmt->bindParam(':selSem', $_POST['selSem'], PDO::PARAM_INT);
                        $stmt->bindParam(':selAY', $_POST['selAY'], PDO::PARAM_INT);
                        $stmt->bindParam(':programID', $_POST['programID'], PDO::PARAM_INT);
                        $stmt->bindParam(':gradelvlID', $_POST['gradelvlID'], PDO::PARAM_INT);
                        $stmt->bindParam(':secID', $_POST['secID'], PDO::PARAM_INT);
                        $stmt->bindParam(':ayName', $_POST['ayName'], PDO::PARAM_STR);
                        $stmt->execute();
                    }
                }else{
                    $sql = "INSERT INTO section_students (adviserID, studID, ayID, programID, gradelvlID, secID, ayName)
                    VALUES (:adviserID, :studID, :selAY, :programID, :gradelvlID, :secID, :ayName)";
                    $stmt = $conn->prepare($sql);
                
                    foreach ($_POST['selStud'] as $studID) {
                        $stmt->bindParam(':adviserID', $facultyID, PDO::PARAM_INT);
                        $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                        $stmt->bindParam(':selAY', $_POST['selAY'], PDO::PARAM_INT);
                        $stmt->bindParam(':programID', $_POST['programID'], PDO::PARAM_INT);
                        $stmt->bindParam(':gradelvlID', $_POST['gradelvlID'], PDO::PARAM_INT);
                        $stmt->bindParam(':secID', $_POST['secID'], PDO::PARAM_INT);
                        $stmt->bindParam(':ayName', $_POST['ayName'], PDO::PARAM_STR);
                        $stmt->execute();
                    }
                }
            
                $conn->commit();
                
                if($deptID == 3){
                    if($isIrreg == 1){
                        header("Location: ../enrolled_students.php?semID=$selSem&secID=$secID&programID=$programID&gradelvlID=$gradelvlID&facultyID=$facultyID&deptID=$deptID");
                    }else{
                        header("Location: ../enrolled_students.php?semID=$selSem&secID=$secID&programID=$programID&gradelvlID=$gradelvlID&facultyID=$facultyID&deptID=$deptID&success=true");
                    }
                }else{
                    header("Location: ../enrolled_students.php?secID=$secID&gradelvlID=$gradelvlID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID&success=true");
                }
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();
            
                error_log("Database error: " . $e->getMessage());
            
                header("Location: ../enrolled_students.php?error=database_error". $e->getMessage());
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