<?php
require_once("includes/config.php");


if(isset($_POST['saveGradeSubBtn'])) {
    $enrollID = $_POST['enrollID'] ?? '';
    $studID = $_POST['studID'] ?? '';
    $subjectID = $_POST['subjectID'] ?? '';
    $subjectName = $_POST['subjectName'] ?? '';
    $programID = $_POST['programID'] ?? '';
    $gradelvlID = $_POST['gradelvlID'] ?? '';
    $semID = $_POST['semID'] ?? '';
    $activeSemID = $_POST['activeSemID'] ?? '';
    $grade = isset($_POST['txtGrade']) && $_POST['txtGrade'] !== '' ? $_POST['txtGrade'] : NULL;
    $grade2 = isset($_POST['txtGrade2']) && $_POST['txtGrade2'] !== '' ? $_POST['txtGrade2'] : NULL;
    $ayName = $_POST['ayName'] ?? '';
    

    $ayID = $_POST['ayID'];
    $studname = $_POST['studname'] ?? '';
    $secID = $_POST['secID'] ?? '';
    $secName = $_POST['secNameSec'] ?? '';
    $deptID = $_POST['deptID'] ?? '';
    $facultyID = $_POST['facultyID'] ?? '';
    $faID = $_POST['faID'] ?? NULL;

    $userTypeID = $_POST['userTypeID'] ?? '';

    if($deptID == 3){
        $finalGrade = ($grade + $grade2) / 2;
    }else{
        $finalGrade;
    }

    try {
        // Update student Grades 
        
        if(($deptID != 3) && ($activeSemID == 2)){ //for non-shs

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

            $query = "UPDATE student_grades SET grade3 = :grade, grade4 = :grade2
                  WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";

            $stmt = $conn->prepare($query);
            $stmt->bindValue(':grade', $grade);
            $stmt->bindValue(':grade2', $grade2);
            $stmt->bindValue(':studID', $studID);
            $stmt->bindValue(':subjectID', $subjectID);
            $stmt->bindValue(':enrollID', $enrollID);
            $stmt->execute();
        }elseif(($deptID != 3) && ($activeSemID == 1)){ //for non-shs
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
            $query = "UPDATE student_grades SET grade = :grade, grade2 = :grade2
                  WHERE enrollID = :enrollID AND studID = :studID AND subjectID = :subjectID ";

            $stmt = $conn->prepare($query);
            $stmt->bindValue(':grade', $grade);
            $stmt->bindValue(':grade2', $grade2);
            $stmt->bindValue(':studID', $studID);
            $stmt->bindValue(':subjectID', $subjectID);
            $stmt->bindValue(':enrollID', $enrollID);
            $stmt->execute();
        }
        
        // Check if the record already exists in student_grades table
        $query_check = "SELECT COUNT(*) FROM student_grades WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bindValue(':studID', value: $studID);
        $stmt_check->bindValue(':subjectID', $subjectID);
        $stmt_check->bindValue(':enrollID', $enrollID);
        $stmt_check->execute();
        $exists = $stmt_check->fetchColumn();

        // If the record does not exist, insert it
        if ($exists == 0) {
            if(($deptID != 3) && ($activeSemID == 2)){
                $query2 = "INSERT INTO student_grades (enrollID, studID, subjectID, gradelvlID, grade3, grade4, ayName, secID) 
                       VALUES (:enrollID, :studID, :subjectID, :gradelvlID, :grade, :grade2, :fgrade, :ayName, :secID)";

                $stmt2 = $conn->prepare($query2);
                $stmt2->bindValue(':enrollID', $enrollID);
                $stmt2->bindValue(':studID', $studID);
                $stmt2->bindValue(':subjectID', $subjectID);
                $stmt2->bindValue(':gradelvlID', $gradelvlID);
                $stmt2->bindValue(':grade', $grade);
                $stmt2->bindValue(':grade2', $grade2);
                $stmt2->bindValue(':ayName', $ayName);
                $stmt2->bindValue(':secID', $secID);
                $stmt2->execute();
            }elseif(($deptID != 3) && ($activeSemID == 1)){
                $query2 = "INSERT INTO student_grades (enrollID, studID, subjectID, gradelvlID, grade, grade2, ayName, secID) 
                       VALUES (:enrollID, :studID, :subjectID, :gradelvlID, :grade, :grade2, :ayName, :secID)";

                $stmt2 = $conn->prepare($query2);
                $stmt2->bindValue(':enrollID', $enrollID);
                $stmt2->bindValue(':studID', $studID);
                $stmt2->bindValue(':subjectID', $subjectID);
                $stmt2->bindValue(':gradelvlID', $gradelvlID);
                $stmt2->bindValue(':grade', $grade);
                $stmt2->bindValue(':grade2', $grade2);
                $stmt2->bindValue(':ayName', $ayName);
                $stmt2->bindValue(':secID', $secID);
                $stmt2->execute();
            }else{
                $query2 = "INSERT INTO student_grades (enrollID, studID, subjectID, semID, gradelvlID, grade, grade2, ayName, secID) 
                       VALUES (:enrollID, :studID, :subjectID, :semID, :gradelvlID, :grade, :grade2, :ayName, :secID)";
            
                $stmt2 = $conn->prepare($query2);
                $stmt2->bindValue(':enrollID', $enrollID);
                $stmt2->bindValue(':studID', $studID);
                $stmt2->bindValue(':subjectID', $subjectID);
                $stmt2->bindValue(':semID', $semID);
                $stmt2->bindValue(':gradelvlID', $gradelvlID);
                $stmt2->bindValue(':grade', $grade);
                $stmt2->bindValue(':grade2', $grade2);
                $stmt2->bindValue(':ayName', $ayName);
                $stmt2->bindValue(':secID', $secID);
                $stmt2->execute();
            }

        }  

                // Update fgrade for semID NOT NULL
                $query_update_fgrade = "
                    UPDATE student_grades
                    SET 
                        fgrade = (COALESCE(grade, 0) + COALESCE(grade2, 0)) / 2,
                        remarks = CASE
                                    WHEN (COALESCE(grade, 0) + COALESCE(grade2, 0)) / 2 < 75 THEN 0  -- Failed
                                    WHEN (COALESCE(grade, 0) + COALESCE(grade2, 0)) / 2 >= 75 THEN 1  -- Passed
                                END
                    WHERE semID IS NOT NULL 
                        AND grade IS NOT NULL 
                        AND grade2 IS NOT NULL;

                ";
                $stmtFgrade = $conn->prepare($query_update_fgrade);
                $stmtFgrade->execute();

                // Update fgrade for semID NULL
                $queryNoSemfgrade = "
                        UPDATE student_grades
                        SET 
                            fgrade = 
                                CASE 
                                    WHEN semID IS NULL 
                                        AND grade IS NOT NULL 
                                        AND grade2 IS NOT NULL 
                                        AND grade3 IS NOT NULL 
                                        AND grade4 IS NOT NULL THEN 
                                            (COALESCE(grade, 0) + COALESCE(grade2, 0) + COALESCE(grade3, 0) + COALESCE(grade4, 0)) / 4
                                    ELSE
                                        fgrade
                                END,
                            remarks = 
                                CASE 
                                    WHEN fgrade IS NOT NULL AND fgrade < 75 THEN 0  -- Failed
                                    WHEN fgrade IS NOT NULL AND fgrade >= 75 THEN 1  -- Passed
                                    ELSE remarks  -- If fgrade is still NULL, do not change remarks
                                END
                        WHERE semID IS NULL
                            AND (grade IS NOT NULL OR grade2 IS NOT NULL OR grade3 IS NOT NULL OR grade4 IS NOT NULL);

                ";
                $stmtFgradeNoSem = $conn->prepare($queryNoSemfgrade);
                $stmtFgradeNoSem->execute();

                    
        switch ($userTypeID) {
            case 1: //admin panel
                header("Location:students_subj.php?studID=$studID&semID=$semID&gradelvlID=$gradelvlID&programID=$programID&subjectID=0&secID=$secID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID");
                break;
            
            case 2: //faculty panel
                header("Location:../faculty/stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&semID=$semID&programID=$programID&gradelvlID=$gradelvlID&deptID=$deptID");
                break;
            
            default:
            header("Location:Error");

                break;
        }
        exit();
    } catch(PDOException $e) {
        echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
    }
}
elseif(isset($_POST['saveGradeSubSubBtn'])) {
    $enrollID = $_POST['enrollID'];
    $studID = $_POST['studID'];
    $subjectID = $_POST['subjectID'];
    $subjectName = $_POST['subjectName'];
    $programID = $_POST['programID'];
    $gradelvlID = $_POST['gradelvlID'];
    $semID = $_POST['semID'] ?? NULL;
    $grade = isset($_POST['txtGrade']) && $_POST['txtGrade'] !== '' ? $_POST['txtGrade'] : NULL;
    $grade2 = isset($_POST['txtGrade2']) && $_POST['txtGrade2'] !== '' ? $_POST['txtGrade2'] : NULL;
    $secID = $_POST['secID'];
    $secName = $_POST['secName'];
    $facultyID = $_POST['facultyID'];
    $facultyName = $_POST['facultyName'];
    $ayID = $_POST['ayID'];
    $studname = $_POST['studname'] ?? '';
    $deptID = $_POST['deptID'];
    $userTypeID = $_POST['userTypeID'];
    $faID = $_POST['faID'] ?? NULL;

    $activeSemID = $_POST['activeSemID'] ?? '';
    $ayName = $_POST['ayName'] ?? '';

        if($deptID == 3){
            $finalGrade = ($grade + $grade2) / 2;
        }else{
            $finalGrade = NULL;
        }

        try {
            // Check if the record already exists in student_grades table
            $query_check = "SELECT * FROM student_grades WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
            $stmt_check = $conn->prepare($query_check);
            $stmt_check->bindValue(':studID', $studID, PDO::PARAM_INT);
            $stmt_check->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
            $stmt_check->bindValue(':enrollID', $enrollID, PDO::PARAM_INT);
            $stmt_check->execute();
            $existing_row = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($existing_row) {
                // Row exists, update the existing record
                    if(($deptID != 3) && ($activeSemID == 2)){

                        $sqlPrevGrades = "SELECT grade, grade2 FROM student_grades
                        WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
                        $stmtGrades = $conn->prepare($sqlPrevGrades);
                        $stmtGrades->bindValue(':studID', $studID);
                        $stmtGrades->bindValue(':subjectID', $subjectID);
                        $stmtGrades->bindValue(':enrollID', $enrollID);
                        $stmtGrades->execute();
                        $resultGrade = $stmtGrades->fetch(PDO::FETCH_ASSOC);

                        $existingGrade = $resultGrade['grade'] ?? NULL;
                        $existingGrade2 = $resultGrade['grade2'] ?? NULL;

                        $finalGrade = ($existingGrade + $existingGrade2 + $grade + $grade2) / 4;

                        $query_update = "UPDATE student_grades SET grade3 = :grade3, grade4 = :grade4, fgrade = :fgrade
                            WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->bindValue(':grade3', $grade, PDO::PARAM_STR);
                        $stmt_update->bindValue(':grade4', $grade2, PDO::PARAM_STR);
                        $stmt_update->bindValue(':fgrade', $finalGrade, PDO::PARAM_STR);
                        $stmt_update->bindValue(':studID', $studID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':enrollID', $enrollID, PDO::PARAM_INT);
                        $stmt_update->execute();
                    }elseif(($deptID != 3) && ($activeSemID == 1)){

                        $sqlPrevGrades = "SELECT grade3, grade4 FROM student_grades
                        WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
                        $stmtGrades = $conn->prepare($sqlPrevGrades);
                        $stmtGrades->bindValue(':studID', $studID);
                        $stmtGrades->bindValue(':subjectID', $subjectID);
                        $stmtGrades->bindValue(':enrollID', $enrollID);
                        $stmtGrades->execute();
                        $resultGrade = $stmtGrades->fetch(PDO::FETCH_ASSOC);

                        $existingGrade = $resultGrade['grade3'];
                        $existingGrade2 = $resultGrade['grade4'];

                        $finalGrade = ($existingGrade + $existingGrade2 + $grade + $grade2) / 4;


                        $query_update = "UPDATE student_grades SET grade = :grade, grade2 = :grade2, gradelvlID = :gradelvlID, semID = :semID, fgrade = :fgrade
                        WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->bindValue(':grade', $grade, PDO::PARAM_STR);
                        $stmt_update->bindValue(':grade2', $grade2, PDO::PARAM_STR);
                        $stmt_update->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_STR);
                        $stmt_update->bindValue(':semID', $semID, PDO::PARAM_STR);
                        $stmt_update->bindValue(':studID', $studID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':enrollID',$enrollID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':fgrade',$finalGrade, PDO::PARAM_INT);
                        $stmt_update->execute();
                    }else{
                        $query_update = "UPDATE student_grades SET grade = :grade, grade2 = :grade2, fgrade = :fgrade, gradelvlID = :gradelvlID, semID = :semID
                        WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->bindValue(':grade', $grade, PDO::PARAM_STR);
                        $stmt_update->bindValue(':grade2', $grade2, PDO::PARAM_STR);
                        $stmt_update->bindValue(':fgrade', $finalGrade, PDO::PARAM_STR);
                        $stmt_update->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_STR);
                        $stmt_update->bindValue(':semID', $semID, PDO::PARAM_STR);
                        $stmt_update->bindValue(':studID', $studID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':enrollID', $enrollID, PDO::PARAM_INT);
                        $stmt_update->execute();
                    }
            } else {
                // Row does not exist, insert a new record

                if(($deptID != 3) && ($activeSemID == 2)){

                    $sqlPrevGrades = "SELECT grade, grade2 FROM student_grades
                    WHERE studID = :studID AND subjectID = :subjectID AND enrollID = :enrollID";
                    $stmtGrades = $conn->prepare($sqlPrevGrades);
                    $stmtGrades->bindValue(':studID', $studID);
                    $stmtGrades->bindValue(':subjectID', $subjectID);
                    $stmtGrades->bindValue(':enrollID', $enrollID);
                    $stmtGrades->execute();
                    $resultGrade = $stmtGrades->fetch(PDO::FETCH_ASSOC);

                    $existingGrade = $resultGrade['grade'] ?? NULL;
                    $existingGrade2 = $resultGrade['grade2'] ?? NULL;

                    $finalGrade = ($existingGrade + $existingGrade2 + $grade + $grade2) / 4;
                    $query_insert = "INSERT INTO student_grades (enrollID, studID, subjectID, gradelvlID, grade3, grade4, secID, ayName, fgrade)
                                    VALUES (:enrollID, :studID, :subjectID, :gradelvlID, :grade3, :grade4, :secID, :ayName, :fgrade)";

                    $stmt_insert = $conn->prepare($query_insert);
                    $stmt_insert->bindValue(':enrollID', $enrollID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':studID', $studID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':grade3', $grade, PDO::PARAM_STR);
                    $stmt_insert->bindValue(':grade4', $grade2, PDO::PARAM_STR);
                    $stmt_insert->bindValue(':secID', $secID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':ayName', $ayName, PDO::PARAM_STR);
                    $stmt_insert->bindValue(':fgrade', $finalGrade, PDO::PARAM_STR);

                }elseif(($deptID != 3) && ($activeSemID == 1)){
                    $query_insert = "INSERT INTO student_grades (enrollID, studID, subjectID, gradelvlID, grade, grade2, secID, ayName)
                                    VALUES (:enrollID, :studID, :subjectID, :gradelvlID, :grade, :grade2, :secID, :ayName)";
                    $stmt_insert = $conn->prepare($query_insert);
                    $stmt_insert->bindValue(':enrollID', $enrollID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':studID', $studID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':grade', $grade, PDO::PARAM_STR);
                    $stmt_insert->bindValue(':grade2', $grade2, PDO::PARAM_STR);
                    $stmt_insert->bindValue(':secID', $secID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':ayName', $ayName, PDO::PARAM_STR);
                }else{
                    $query_insert = "INSERT INTO student_grades (enrollID, studID, subjectID, gradelvlID, semID, grade, grade2, secID, ayName)
                    VALUES (:enrollID, :studID, :subjectID, :gradelvlID, :semID, :grade, :grade2, :secID, :ayName)";
                    $stmt_insert = $conn->prepare($query_insert);
                    $stmt_insert->bindValue(':enrollID', $enrollID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':studID', $studID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':semID', $semID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':grade', $grade, PDO::PARAM_STR);
                    $stmt_insert->bindValue(':grade2', $grade2, PDO::PARAM_STR);
                    $stmt_insert->bindValue(':secID', $secID, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':ayName', $ayName, PDO::PARAM_STR);
                }

                $stmt_insert->execute();
            }

                // Update fgrade for semID NOT NULL
                    $query_update_fgrade = "
                    UPDATE student_grades
                                SET 
                                    fgrade = (COALESCE(grade, 0) + COALESCE(grade2, 0)) / 2,
                                    remarks = CASE
                                                WHEN (COALESCE(grade, 0) + COALESCE(grade2, 0)) / 2 < 75 THEN 0  -- Failed
                                                WHEN (COALESCE(grade, 0) + COALESCE(grade2, 0)) / 2 >= 75 THEN 1  -- Passed
                                            END
                                WHERE semID IS NOT NULL 
                                    AND grade IS NOT NULL 
                                AND grade2 IS NOT NULL;

                ";
                $stmtFgrade = $conn->prepare($query_update_fgrade);
                $stmtFgrade->execute();

                // Update fgrade for semID NULL
                $queryNoSemfgrade = "
                    UPDATE student_grades
                    SET 
                        fgrade = 
                            CASE 
                                WHEN semID IS NULL 
                                    AND grade IS NOT NULL 
                                    AND grade2 IS NOT NULL 
                                    AND grade3 IS NOT NULL 
                                    AND grade4 IS NOT NULL THEN 
                                        (COALESCE(grade, 0) + COALESCE(grade2, 0) + COALESCE(grade3, 0) + COALESCE(grade4, 0)) / 4
                                ELSE
                                    fgrade
                            END,
                        remarks = 
                            CASE 
                                WHEN fgrade IS NOT NULL AND fgrade < 75 THEN 0  -- Failed
                                WHEN fgrade IS NOT NULL AND fgrade >= 75 THEN 1  -- Passed
                                ELSE remarks  -- If fgrade is still NULL, do not change remarks
                            END
                    WHERE semID IS NULL
                        AND (grade IS NOT NULL OR grade2 IS NOT NULL OR grade3 IS NOT NULL OR grade4 IS NOT NULL);

                ";
                $stmtFgradeNoSem = $conn->prepare($queryNoSemfgrade);
                $stmtFgradeNoSem->execute();





            if ($userTypeID == 1) {
                // Redirect to the student.php page with necessary parameters
                if($deptID == 3){
                    header("Location: student.php?secID=$secID&programID=$programID&subjectID=$subjectID&gradelvlID=$gradelvlID&facultyID=$facultyID&ayID=$ayID&semID=$semID&studID=$studID&deptID=$deptID");
                }elseif($deptID == 2){
                    header("Location: student.php?secID=$secID&subjectID=$subjectID&gradelvlID=$gradelvlID&facultyID=$facultyID&ayID=$ayID&deptID=$deptID&studID=$studID");
                }
            }else{
                // Redirect to the student.php page with necessary parameters
                if($deptID == 3){
                    header("Location: ../faculty/students.php?subjectID=$subjectID&facultyID=$facultyID&faID=$faID&secID=$secID&deptID=$deptID&semID=$semID&programID=$programID");
                }else{
                    header("Location: ../faculty/students.php?subjectID=$subjectID&facultyID=$facultyID&faID=$faID&secID=$secID&deptID=$deptID");
                }
            }

            exit();

        } catch(PDOException $e) {
            echo '<script>alert("Database Error: ' . $e->getMessage() . '");</script>';
        }
    
}
elseif(isset($_POST['btnSaveGrade'])){
    $ayName = $_POST['txtAYName'] ?? NULL;
    $semID = $_POST['selSem'] ?? NULL;
    $studID = $_POST['studID'] ?? NULL;
    $subjectID = $_POST['subjectID'] ?? NULL;
    $deptID = $_POST['deptID'] ?? NULL;
    $grade = isset($_POST['firstInput']) && $_POST['firstInput'] !== '' ? $_POST['firstInput'] : NULL;
    $grade2 = isset($_POST['secondInput']) && $_POST['secondInput'] !== '' ? $_POST['secondInput'] : NULL;


    $enrollID = $_POST['enrollID'] ?? NULL;
    $gradelvlID = $_POST['gradelvlID'] ?? NULL;
    $secID = $_POST['secID'] ?? NULL;

    if($deptID == 3){
        $finalGrade = ($grade + $grade2) / 2;
    }else{
        $finalGrade = NULL;
    }

        try {
            // Check if the record already exists in student_grades table
            $query_check = "SELECT * FROM student_grades WHERE studID = :studID AND subjectID = :subjectID AND ayName = :ayName";
            $stmt_check = $conn->prepare($query_check);
            $stmt_check->bindValue(':studID', $studID, PDO::PARAM_INT);
            $stmt_check->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
            $stmt_check->bindValue(':ayName', $ayName, PDO::PARAM_INT);
            $stmt_check->execute();
            $existing_row = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($existing_row) {
                // Row exists, update the existing record
                    if(($deptID != 3) && ($semID == 2)){

                        $sqlPrevGrades = "SELECT grade, grade2 FROM student_grades
                        WHERE studID = :studID AND subjectID = :subjectID AND ayName = :ayName";
                        $stmtGrades = $conn->prepare($sqlPrevGrades);
                        $stmtGrades->bindValue(':studID', $studID);
                        $stmtGrades->bindValue(':subjectID', $subjectID);
                        $stmtGrades->bindValue(':ayName', $ayName, PDO::PARAM_STR);
                        $stmtGrades->execute();
                        $resultGrade = $stmtGrades->fetch(PDO::FETCH_ASSOC);

                        $existingGrade = $resultGrade['grade'] ?? NULL;
                        $existingGrade2 = $resultGrade['grade2'] ?? NULL;

                        $finalGrade = ($existingGrade + $existingGrade2 + $grade + $grade2) / 4;

                        $query_update = "UPDATE student_grades SET grade3 = :grade3, grade4 = :grade4
                            WHERE studID = :studID AND subjectID = :subjectID AND ayName = :ayName";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->bindValue(':grade3', $grade, PDO::PARAM_STR);
                        $stmt_update->bindValue(':grade4', $grade2, PDO::PARAM_STR);
                        $stmt_update->bindValue(':studID', $studID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':ayName', $ayName, PDO::PARAM_STR);
                        $stmt_update->execute();
                    }elseif(($deptID != 3) && ($semID == 1)){

                        $sqlPrevGrades = "SELECT grade3, grade4 FROM student_grades
                        WHERE studID = :studID AND subjectID = :subjectID AND ayName = :ayName";
                        $stmtGrades = $conn->prepare($sqlPrevGrades);
                        $stmtGrades->bindValue(':studID', $studID);
                        $stmtGrades->bindValue(':subjectID', $subjectID);
                        $stmtGrades->bindValue(':ayName', $ayName, PDO::PARAM_STR);
                        $stmtGrades->execute();
                        $resultGrade = $stmtGrades->fetch(PDO::FETCH_ASSOC);

                        $existingGrade = $resultGrade['grade3'];
                        $existingGrade2 = $resultGrade['grade4'];

                        $finalGrade = ($existingGrade + $existingGrade2 + $grade + $grade2) / 4;


                        $query_update = "UPDATE student_grades SET grade = :grade, grade2 = :grade2
                        WHERE studID = :studID AND subjectID = :subjectID AND ayName = :ayName";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->bindValue(':grade', $grade, PDO::PARAM_STR);
                        $stmt_update->bindValue(':grade2', $grade2, PDO::PARAM_STR);
                        $stmt_update->bindValue(':studID', $studID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':ayName', $ayName, PDO::PARAM_STR);
                        $stmt_update->execute();
                    }else{
                        $query_update = "UPDATE student_grades SET grade = :grade, grade2 = :grade2
                        WHERE studID = :studID AND subjectID = :subjectID AND ayName = :ayName";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->bindValue(':grade', $grade, PDO::PARAM_STR);
                        $stmt_update->bindValue(':grade2', $grade2, PDO::PARAM_STR);
                        $stmt_update->bindValue(':studID', $studID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                        $stmt_update->bindValue(':ayName', $ayName, PDO::PARAM_STR);
                        $stmt_update->execute();
                    }
            } else {
                
            }


            try {
                // Check if the referrer exists
                $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'defaultPage.php';
            
                // Redirect to the previous page (or default page if no referrer is found)
                header("Location: $referrer");
                exit();
            } catch (Exception $e) {
                echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
            }
            


        } catch(PDOException $e) {
            echo '<script>alert("Database Error: ' . $e->getMessage() . '");</script>';
        }
}
?>