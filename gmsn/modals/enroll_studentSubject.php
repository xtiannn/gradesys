<?php
require_once("../includes/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enrollSubBtn'])) {
    $studID = $_POST['studID'] ?? null;
    $programID = $_POST['programID'] ?? null;
    $gradelvlID = $_POST['gradelvlID'] ?? null;
    $selectedSubjects = $_POST['selSub'] ?? null;
    $ayID = $_POST['selAY'] ?? null;
    $secID = $_POST['secID'] ?? null;
    $secName = $_POST['secName'] ?? null;
    $deptID = $_POST['deptID'] ?? null;
    $facultyID = $_POST['facultyID'] ?? null;
    $studName = $_POST['studName'] ?? null;
    $ayName = $_POST['ayName'] ?? null;


    if($deptID == 3){
        $semID = $_POST['semID'] ?? null;
    }else{
        $semID = NULL;
    }

    try {
        $conn->beginTransaction();
        // Insert new enrollments
        foreach ($selectedSubjects as $subjectID) {


            if($deptID == 3){
                $query = "INSERT INTO section_students (studID, subjectID, semID, gradelvlID, programID, ayID, secID, ayName) 
                        VALUES (:studID, :subjectID, :semID, :gradelvlID, :programID, :ayID, :secID, :ayName)";
                
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
                $stmt->bindParam(':ayID', $ayID, PDO::PARAM_INT);
                $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                $stmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);
                $stmt->execute();
            
            }else{
                $query = "INSERT INTO section_students (studID, subjectID, gradelvlID, programID, ayID, secID, ayName) 
                        VALUES (:studID, :subjectID, :gradelvlID, :programID, :ayID, :secID, :ayName)";
                
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
                $stmt->bindParam(':ayID', $ayID, PDO::PARAM_INT);
                $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                $stmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);
                $stmt->execute();
            }

        }

        $conn->commit();
        $url = sprintf(
            "../students_subj.php?studID=%s&semID=%s&secID=%s&gradelvlID=%s&programID=%s&subjectID=%s&ayID=%s&facultyID=%s&deptID=%s",
            urlencode(trim($studID)),
            urlencode(trim($semID)),
            urlencode(trim($secID)),
            urlencode(trim($gradelvlID)),
            urlencode(trim($programID)),
            urlencode(0), // Hardcoded subjectID
            urlencode(trim($ayID)),
            urlencode(trim($facultyID)),
            urlencode(trim($deptID))
        );
        
        header("Location: $url");
        exit();
        
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Failed to enroll subjects: " . $e->getMessage();
    }
}
?>
