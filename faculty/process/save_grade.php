<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../includes/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enrollID = $_POST['enrollID'] ?? '';
    $studID = $_POST['studID'] ?? '';
    $subjectID = $_POST['subjectID'] ?? '';
    $subjectName = $_POST['subjectName'] ?? '';
    $programID = $_POST['programID'] ?? '';
    $gradelvlID = $_POST['gradelvlID'] ?? NULL;
    $semID = $_POST['semID'] ?? '';
    $ayID = $_POST['ayID'] ?? NULL;
    $studname = $_POST['studname'] ?? '';
    $secID = $_POST['secID'] ?? '';
    $deptID = $_POST['deptID'] ?? '';
    $facultyID = $_POST['facultyID'] ?? '';
    $secName = $_POST['secName'] ?? '';
    $ayName = $_POST['ayName'] ?? NULL;
    $faID = $_POST['faID'] ?? '';

    $userTypeID = $_SESSION['userTypeID'] ?? '';

    $grade = isset($_POST['txtGrade']) && $_POST['txtGrade'] !== '' ? $_POST['txtGrade'] : NULL;
    $grade2 = isset($_POST['txtGrade2']) && $_POST['txtGrade2'] !== '' ? $_POST['txtGrade2'] : NULL;

    if($deptID == 3){
        $finalGrade = ($grade + $grade2) / 2;
    }else{
        $finalGrade;
    }


    try {
        if(($deptID != 3) && ($semID == 2)){

            $sqlPrevGrades = "SELECT grade, grade2 FROM student_grades
                            WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
            $stmtGrades = $conn->prepare($sqlPrevGrades);
            $stmtGrades->bindValue(':studID', $studID);
            $stmtGrades->bindValue(':subjectID', $subjectID);
            $stmtGrades->bindValue(':enrollID', $enrollID);
            $stmtGrades->execute();
            $resultGrade = $stmtGrades->fetch(PDO::FETCH_ASSOC);

            $existingGrade = $resultGrade['grade'];
            $existingGrade2 = $resultGrade['grade2'];

            $finalGrade = ($existingGrade + $existingGrade + $grade + $grade2) / 4;

            $query = "UPDATE student_grades SET grade3 = :grade, grade4 = :grade2, fgrade = :fgrade
                  WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";

            $stmt = $conn->prepare($query);
            $stmt->bindValue(':grade', $grade);
            $stmt->bindValue(':grade2', $grade2);
            $stmt->bindValue(':studID', $studID);
            $stmt->bindValue(':subjectID', $subjectID);
            $stmt->bindValue(':enrollID', $enrollID);
            $stmt->bindValue(':fgrade', $finalGrade);
            $stmt->execute();
        }elseif(($deptID != 3) && ($semID == 1)){
            $query = "UPDATE student_grades SET grade = :grade, grade2 = :grade2
                  WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";

            $stmt = $conn->prepare($query);
            $stmt->bindValue(':grade', $grade);
            $stmt->bindValue(':grade2', $grade2);
            $stmt->bindValue(':studID', $studID);
            $stmt->bindValue(':subjectID', $subjectID);
            $stmt->bindValue(':enrollID', $enrollID);
            $stmt->execute();
        }else{
            // Update student Grades 
            $query = "UPDATE student_grades SET grade = :grade, grade2 = :grade2, fgrade = :fgrade
            WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':grade', $grade);
            $stmt->bindValue(':grade2', $grade2);
            $stmt->bindValue(':studID', $studID);
            $stmt->bindValue(':subjectID', $subjectID);
            $stmt->bindValue(':enrollID', $enrollID);
            $stmt->bindValue(':fgrade', $finalGrade);
            $stmt->execute();
        }

        // Check if the record already exists in student_grades table
        $query_check = "SELECT COUNT(*) FROM student_grades WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bindValue(':studID', $studID);
        $stmt_check->bindValue(':subjectID', $subjectID);
        $stmt_check->bindValue(':enrollID', $enrollID);
        $stmt_check->execute();
        $exists = $stmt_check->fetchColumn();

        // If the record does not exist, insert it
        if ($exists == 0) {
            if(($deptID != 3) && ($semID == 2)){

                $sqlPrevGrades = "SELECT grade, grade2 FROM student_grades
                            WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
                $stmtGrades = $conn->prepare($sqlPrevGrades);
                $stmtGrades->bindValue(':studID', $studID);
                $stmtGrades->bindValue(':subjectID', $subjectID);
                $stmtGrades->bindValue(':enrollID', $enrollID);
                $stmtGrades->execute();
                $resultGrade = $stmtGrades->fetch(PDO::FETCH_ASSOC);

                $existingGrade = $resultGrade['grade'];
                $existingGrade2 = $resultGrade['grade2'];

                $finalGrade = ($existingGrade + $existingGrade + $grade + $grade2) / 4;
                
                $query2 = "INSERT INTO student_grades (enrollID, studID, subjectID, semID, gradelvlID, grade3, grade4, fgrade) 
                            VALUES (:enrollID, :studID, :subjectID, :semID, :gradelvlID, :grade, :grade2, :fgrade)";
                $stmt2 = $conn->prepare($query2);
                $stmt2->bindValue(':enrollID', $enrollID);
                $stmt2->bindValue(':studID', $studID);
                $stmt2->bindValue(':subjectID', $subjectID);
                $stmt2->bindValue(':semID', $semID);
                $stmt2->bindValue(':gradelvlID', $gradelvlID);
                $stmt2->bindValue(':grade', $grade);
                $stmt2->bindValue(':grade2', $grade2);
                $stmt2->bindValue(':fgrade', $finalGrade);
                $stmt2->execute();
            }elseif(($deptID != 3) && ($semID == 1)){
                $query2 = "INSERT INTO student_grades (enrollID, studID, subjectID, semID, gradelvlID, grade, grade2) 
                    VALUES (:enrollID, :studID, :subjectID, :semID, :gradelvlID, :grade, :grade2)";

                $stmt2 = $conn->prepare($query2);
                $stmt2->bindValue(':enrollID', $enrollID);
                $stmt2->bindValue(':studID', $studID);
                $stmt2->bindValue(':subjectID', $subjectID);
                $stmt2->bindValue(':semID', $semID);
                $stmt2->bindValue(':gradelvlID', $gradelvlID);
                $stmt2->bindValue(':grade', $grade);
                $stmt2->bindValue(':grade2', $grade2);
                $stmt2->execute();
            }else{
                $query2 = "INSERT INTO student_grades (enrollID, studID, subjectID, semID, gradelvlID, grade, grade2, fgrade) 
                VALUES (:enrollID, :studID, :subjectID, :semID, :gradelvlID, :grade, :grade2, :fgrade)";
                $stmt2 = $conn->prepare($query2);
                $stmt2->bindValue(':enrollID', $enrollID);
                $stmt2->bindValue(':studID', $studID);
                $stmt2->bindValue(':subjectID', $subjectID);
                $stmt2->bindValue(':semID', $semID);
                $stmt2->bindValue(':gradelvlID', $gradelvlID);
                $stmt2->bindValue(':grade', $grade);
                $stmt2->bindValue(':grade2', $grade2);
                $stmt2->bindValue(':fgrade', $finalGrade);
                $stmt2->execute();
           }
        }

        $encodedSubjectID = urlencode($subjectID);
        $encodedFacultyID = urlencode($facultyID);
        $encodedSecName = urlencode($secName);
        $encodedAyName = urlencode($ayName);
        $encodedFaID = urlencode($faID);
        
        if($userTypeID == 2){ //faculty panel
            $redirectUrl = "../students.php?subjectID=$encodedSubjectID&facultyID=$encodedFacultyID&secName=$encodedSecName&faID=$encodedFaID&secID=$secID&deptID=$deptID";   

        }else{
            $redirectUrl = "../students.php?subjectID=$encodedSubjectID&facultyID=$encodedFacultyID&secName=$encodedSecName&ayName=$encodedAyName&faID=$encodedFaID";   

        }
        header("Location: $redirectUrl");     
        exit();
    } catch(PDOException $e) {
        echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
    }
}
?>
