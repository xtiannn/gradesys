<?php
require_once("includes/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollID = $_POST['enrollID'];
    $studID = $_POST['studID'];
    $subjectID = $_POST['subjectID'];
    $programID = $_POST['programID'];
    $gradelvlID = $_POST['gradelvlID'];
    $semID = $_POST['semID'];
    $grade = isset($_POST['txtGrade']) && $_POST['txtGrade'] !== '' ? $_POST['txtGrade'] : 0;
    $grade2 = isset($_POST['txtGrade2']) && $_POST['txtGrade2'] !== '' ? $_POST['txtGrade2'] : 0;
    $finalGrade = ($grade + $grade2) / 2;
    $ayID = $_POST['ayID'];
    $studname = $_POST['studname'];
    $secID = $_POST['secID'];
    $subjectName = $_POST['subjectName'];

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

        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
