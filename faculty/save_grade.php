<?php
require_once("includes/config.php");


if(isset($_POST['saveGradeSubBtn'])) {
    $enrollID = $_POST['enrollID'];
    $studID = $_POST['studID'];
    $subjectID = $_POST['subjectID'];
    $subjectName = $_POST['subjectName'];
    $programID = $_POST['programID'];
    $gradelvlID = $_POST['gradelvlID'];
    $semID = $_POST['semID'];
    $grade = isset($_POST['txtGrade']) && $_POST['txtGrade'] !== '' ? $_POST['txtGrade'] : 0;
    $grade2 = isset($_POST['txtGrade2']) && $_POST['txtGrade2'] !== '' ? $_POST['txtGrade2'] : 0;
    $finalGrade = ($grade + $grade2) / 2;
    $ayID = $_POST['ayID'];
    $studname = $_POST['studname'];
    $secID = $_POST['secID'];

    try {
        // Update section_students table
        $query = "UPDATE section_students SET grade = :grade, grade2 = :grade2, fgrade = :fgrade
                  WHERE studID = :studID AND subjectID = :subjectID";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':grade', $grade);
        $stmt->bindValue(':grade2', $grade2);
        $stmt->bindValue(':studID', $studID);
        $stmt->bindValue(':subjectID', $subjectID);
        $stmt->bindValue(':fgrade', $finalGrade);
        $stmt->execute();

        // Check if the record already exists in student_grades table
        $query_check = "SELECT COUNT(*) FROM student_grades WHERE studID = :studID AND subjectID = :subjectID";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bindValue(':studID', $studID);
        $stmt_check->bindValue(':subjectID', $subjectID);
        $stmt_check->execute();
        $exists = $stmt_check->fetchColumn();

        // If the record does not exist, insert it
        if ($exists == 0) {
            $query2 = "INSERT INTO student_grades (studID, subjectID, semID, gradelvlID, grade, grade2, fgrade) 
                       VALUES (:studID, :subjectID, :semID, :gradelvlID, :grade, :grade2, :fgrade)";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bindValue(':studID', $studID);
            $stmt2->bindValue(':subjectID', $subjectID);
            $stmt2->bindValue(':semID', $semID);
            $stmt2->bindValue(':gradelvlID', $gradelvlID);
            $stmt2->bindValue(':grade', $grade);
            $stmt2->bindValue(':grade2', $grade2);
            $stmt2->bindValue(':fgrade', $finalGrade);
            $stmt2->execute();
        }

        header("Location:students_subj.php?studID=$studID&studName=$studname&semID=$semID&gradelvlID=$gradelvlID&programID=$programID&subjectID=0&secID=$secID&secName=$secName&ayID=$ayID");
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
    $semID = $_POST['semID'];
    $grade = isset($_POST['txtGrade']) && $_POST['txtGrade'] !== '' ? $_POST['txtGrade'] : 0;
    $grade2 = isset($_POST['txtGrade2']) && $_POST['txtGrade2'] !== '' ? $_POST['txtGrade2'] : 0;
    $secID = $_POST['secID'];
    $secName = $_POST['secName'];
    $facultyID = $_POST['facultyID'];
    $facultyName = $_POST['facultyName'];
    $ayID = $_POST['ayID'];
    $studname = $_POST['studname'];

    // Calculate final grade
    $finalGrade = ($grade + $grade2) / 2;

        try {
            // Check if the record already exists in student_grades table
            $query_check = "SELECT * FROM student_grades WHERE studID = :studID AND subjectID = :subjectID";
            $stmt_check = $conn->prepare($query_check);
            $stmt_check->bindValue(':studID', $studID, PDO::PARAM_INT);
            $stmt_check->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
            $stmt_check->execute();
            $existing_row = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($existing_row) {
                // Row exists, update the existing record
                $query_update = "UPDATE student_grades SET grade = :grade, grade2 = :grade2, fgrade = :fgrade
                                 WHERE studID = :studID AND subjectID = :subjectID";
                $stmt_update = $conn->prepare($query_update);
                $stmt_update->bindValue(':grade', $grade, PDO::PARAM_STR);
                $stmt_update->bindValue(':grade2', $grade2, PDO::PARAM_STR);
                $stmt_update->bindValue(':fgrade', $finalGrade, PDO::PARAM_STR);
                $stmt_update->bindValue(':studID', $studID, PDO::PARAM_INT);
                $stmt_update->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmt_update->execute();
            } else {
                // Row does not exist, insert a new record
                $query_insert = "INSERT INTO student_grades (studID, subjectID, grade, grade2, fgrade)
                                 VALUES (:studID, :subjectID, :grade, :grade2, :fgrade)";
                $stmt_insert = $conn->prepare($query_insert);
                $stmt_insert->bindValue(':studID', $studID, PDO::PARAM_INT);
                $stmt_insert->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmt_insert->bindValue(':grade', $grade, PDO::PARAM_STR);
                $stmt_insert->bindValue(':grade2', $grade2, PDO::PARAM_STR);
                $stmt_insert->bindValue(':fgrade', $finalGrade, PDO::PARAM_STR);
                $stmt_insert->execute();
            }

            // Update existing record in section_students table
            $query_update_section = "UPDATE section_students SET grade = :grade, grade2 = :grade2, fgrade = :fgrade
                                     WHERE studID = :studID AND subjectID = :subjectID";
            $stmt_update_section = $conn->prepare($query_update_section);
            $stmt_update_section->bindValue(':grade', $grade, PDO::PARAM_STR);
            $stmt_update_section->bindValue(':grade2', $grade2, PDO::PARAM_STR);
            $stmt_update_section->bindValue(':fgrade', $finalGrade, PDO::PARAM_STR);
            $stmt_update_section->bindValue(':studID', $studID, PDO::PARAM_INT);
            $stmt_update_section->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
            $stmt_update_section->execute();

            // Redirect to the student.php page with necessary parameters
            header("Location: student.php?secID=$secID&secName=$secName&subjectName=$subjectName&programID=$programID&subjectID=$subjectID&gradelvlID=$gradelvlID&facultyID=$facultyID&ayID=$ayID&facultyName=$facultyName&semID=$semID&studID=$studID");
            exit();

        } catch(PDOException $e) {
            echo '<script>alert("Database Error: ' . $e->getMessage() . '");</script>';
        }
    
}
?>