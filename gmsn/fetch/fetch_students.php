<?php
require_once "../includes/config.php";

// Fetch students based on AY and semester
if (isset($_POST['txtAYName']) && isset($_POST['semID'])) {
    $txtAYName = $_POST['txtAYName'];
    $semID = $_POST['semID'];

    $stmt = $conn->prepare("SELECT DISTINCT s.lrn, ss.studID, CONCAT(s.lname, ', ', s.fname, ' ', LEFT(s.mname, 1), '.') AS fullName, ss.ayName
    FROM section_students ss 
    JOIN students s ON ss.studID = s.studID 
    WHERE ss.ayName = :txtAYName AND (ss.semID = :semID OR ss.semID IS NULL)
    ORDER BY s.lname, s.fname");
    $stmt->execute(['txtAYName' => $txtAYName, 'semID' => $semID]);
    $students = $stmt->fetchAll();

    // Generate the HTML options for students
    if ($students) {
        echo '<option value="" selected disabled>Select Student</option>';
        foreach ($students as $student) {
            $studentLRN = $student['lrn'];
            echo '<option value="' . htmlspecialchars($student['studID']) . '" data-subtext="LRN : '.$studentLRN . '">' . htmlspecialchars(ucwords(strtolower($student['fullName']))) . '</option>';
        }
    } else {
        echo '<option disabled>No students found for this term.</option>';
    }
} 

// Fetch subjects based on selected student, AY, and semester
if (isset($_POST['studID']) && isset($_POST['ayName']) && isset($_POST['semID'])) {
    $studID = $_POST['studID'];
    $ayName = $_POST['ayName'];
    $semID = $_POST['semID'];

    try {
        // Query to fetch subjects based on student ID
        $sql = "
            SELECT ss.subjectID, s.subjectname
            FROM section_students ss
            JOIN subjects s ON ss.subjectID = s.subjectID
            WHERE ss.studID = :selectedStudID
            AND ss.ayName = :selectedAyName
            AND ss.semID = :selectedSemID
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':selectedStudID', $studID, PDO::PARAM_INT);
        $stmt->bindParam(':selectedAyName', $ayName, PDO::PARAM_STR); 
        $stmt->bindParam(':selectedSemID', $semID, PDO::PARAM_INT);
        $stmt->execute();

        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate the HTML options for subjects
        if ($subjects) {
            echo '<option value="" selected disabled>Select Subject:</option>';
            foreach ($subjects as $subject) {
                echo '<option value="' . $subject['subjectID'] . '">' . htmlspecialchars($subject['subjectname']) . '</option>';
            }
        } else {
            echo '<option disabled>No subjects found for this student.</option>';
        }
    } catch (PDOException $e) {
        echo '<option disabled>Error fetching subjects: ' . $e->getMessage() . '</option>';
    }
}
?>
