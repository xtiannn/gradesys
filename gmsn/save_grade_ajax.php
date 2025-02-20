<?php
require_once("includes/config.php");


if (isset($_POST['enrollID'], $_POST['field'], $_POST['value'])) {
    $enrollID = $_POST['enrollID'];
    $studID = $_POST['studID'];
    $subjectID = $_POST['subjectID'];
    $semID = $_POST['semID'];
    $gradelvlID = $_POST['gradelvlID'];
    $facultyID = $_POST['facultyID'];
    $deptID = $_POST['deptID'];
    $secID = $_POST['secID'];
    $ayName = $_POST['ayName'];
    $field = $_POST['field'];
    $value = $_POST['value'];


    if($deptID == 3){

        if ($field == 'grade' || $field == 'grade2') {
            $value = floatval($value);  
        }
        if ($value == NULL || $value == NULL) {
            $value = NULL;  
        }
    
        // Check if a record already exists for this enrollID
        $checkQuery = "SELECT studID, subjectID, secID, ayName, gradelvlID, semID 
                        FROM student_grades 
                        WHERE studID = :studID 
                        AND subjectID = :subjectID 
                        AND secID = :secID 
                        AND ayName = :ayName 
                        AND gradelvlID = :gradelvlID 
                        AND semID = :semID ";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindValue(':studID', $studID, PDO::PARAM_INT);
        $checkStmt->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
        $checkStmt->bindValue(':secID', $secID, PDO::PARAM_INT);
        $checkStmt->bindValue(':ayName', $ayName, PDO::PARAM_INT);
        $checkStmt->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
        $checkStmt->bindValue(':semID', $semID, PDO::PARAM_INT);
        $checkStmt->execute();
    
        if ($checkStmt->rowCount() > 0) {
            // Record exists, so update the grade
            $updateQuery = "UPDATE student_grades SET $field = :value 
                        WHERE studID = :studID 
                        AND subjectID = :subjectID 
                        AND secID = :secID 
                        AND ayName = :ayName 
                        AND gradelvlID = :gradelvlID 
                        AND semID = :semID ";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindValue(':value', $value, PDO::PARAM_STR);
            $updateStmt->bindValue(':studID', $studID, PDO::PARAM_INT);
            $updateStmt->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
            $updateStmt->bindValue(':secID', $secID, PDO::PARAM_INT);
            $updateStmt->bindValue(':ayName', $ayName, PDO::PARAM_INT);
            $updateStmt->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
            $updateStmt->bindValue(':semID', $semID, PDO::PARAM_INT);
            $updateStmt->execute();
    
            // Execute the query to update fgrade after a successful update
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
    
            echo "Grade updated successfully.";
        } else {
            // Record does not exist, so insert a new row
            $insertQuery = "INSERT INTO student_grades (secID, ayName, studID, enrollID, subjectID, semID, gradelvlID, $field) 
                            VALUES (:secID, :ayName, :studID, :enrollID, :subjectID, :semID, :gradelvlID, :value)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bindValue(':secID', $secID, PDO::PARAM_INT);
            $insertStmt->bindValue(':ayName', $ayName, PDO::PARAM_STR);
            $insertStmt->bindValue(':studID', $studID, PDO::PARAM_INT);
            $insertStmt->bindValue(':enrollID', $enrollID, PDO::PARAM_INT);
            $insertStmt->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
            $insertStmt->bindValue(':semID', $semID, PDO::PARAM_INT);
            $insertStmt->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
            $insertStmt->bindValue(':value', $value, PDO::PARAM_STR);
            $insertStmt->execute();
    
    
            echo "Grade inserted successfully.";
        }
        
    }else{

        if ($field == 'grade' || $field == 'grade2' || $field == 'grade3' || $field == 'grade4') {
            $value = floatval($value);  
        }
        if ($value == NULL || $value == NULL || $value == NULL || $value == NULL) {
            $value = NULL;  
        }
    
        // Check if a record already exists for this enrollID
        $checkQuery = "SELECT studID, subjectID, secID, ayName, gradelvlID, semID 
                        FROM student_grades WHERE studID = :studID 
                        AND subjectID = :subjectID 
                        AND secID = :secID 
                        AND ayName = :ayName 
                        AND gradelvlID = :gradelvlID";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindValue(':studID', $studID, PDO::PARAM_INT);
        $checkStmt->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
        $checkStmt->bindValue(':secID', $secID, PDO::PARAM_INT);
        $checkStmt->bindValue(':ayName', $ayName, PDO::PARAM_INT);
        $checkStmt->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            // Record exists, so update the grade
            $updateQuery = "UPDATE student_grades SET $field = :value  WHERE studID = :studID 
                        AND subjectID = :subjectID 
                        AND secID = :secID 
                        AND ayName = :ayName 
                        AND gradelvlID = :gradelvlID ";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindValue(':value', $value, PDO::PARAM_STR);
            $updateStmt->bindValue(':studID', $studID, PDO::PARAM_INT);
            $updateStmt->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
            $updateStmt->bindValue(':secID', $secID, PDO::PARAM_INT);
            $updateStmt->bindValue(':ayName', $ayName, PDO::PARAM_INT);
            $updateStmt->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
            $updateStmt->execute();
    
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
    
            echo "Grade updated successfully.";
        } else {
            // Record does not exist, so insert a new row
            $insertQuery = "INSERT INTO student_grades (secID, ayName, studID, enrollID, subjectID, gradelvlID, $field) 
                            VALUES (:secID, :ayName, :studID, :enrollID, :subjectID, :gradelvlID, :value)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bindValue(':secID', $secID, PDO::PARAM_INT);
            $insertStmt->bindValue(':ayName', $ayName, PDO::PARAM_STR);
            $insertStmt->bindValue(':studID', $studID, PDO::PARAM_INT);
            $insertStmt->bindValue(':enrollID', $enrollID, PDO::PARAM_INT);
            $insertStmt->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
            $insertStmt->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
            $insertStmt->bindValue(':value', $value, PDO::PARAM_STR);
            $insertStmt->execute();
    
            echo "Grade inserted successfully.";
        }
    }

} else {
    echo "Invalid request.";
}
?>
