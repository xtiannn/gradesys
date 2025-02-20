<?php
require_once("../includes/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enrollSubBtn'])) {
    $studID = $_POST['studID'];
    $programID = $_POST['programID'];
    $semID = $_POST['semID'];
    $gradelvlID = $_POST['gradelvlID'];
    $selectedSubjects = $_POST['selSub'];
    $ayID = $_POST['selAY'];
    $secID = $_POST['secID'];
    $secName = $_POST['secName'];

    try {
        $conn->beginTransaction();
        // Insert new enrollments
        foreach ($selectedSubjects as $subjectID) {
            $query = "INSERT INTO section_students (studID, subjectID, semID, gradelvlID, programID, ayID, secID) VALUES (:studID, :subjectID, :semID, :gradelvlID, :programID, :ayID, :secID)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
            $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
            $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
            $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
            $stmt->bindParam(':ayID', $ayID, PDO::PARAM_INT);
            $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
            $stmt->execute();
        }

        $conn->commit();
        header("Location: ../students_subj.php?studID=$studID&semID=$semID&gradelvlID=$gradelvlID&programID=$programID&subjectID=0&secID=$secID&ayID=$ayID&secName=$secName");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Failed to enroll subjects: " . $e->getMessage();
    }
}
?>
