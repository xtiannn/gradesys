<?php
require_once "../includes/config.php";

if (isset($_POST['studID']) && isset($_POST['ayName']) && isset($_POST['semID'])) {
    // Get the POST data
    $studID = $_POST['studID'];
    $ayName = $_POST['ayName'];
    $semID = $_POST['semID'];

    try {
        // SQL query to fetch subjects based on student, academic year, and semester
        $SQL = "
                SELECT DISTINCT ss.subjectID, s.subjectname, sc.secName, sc.deptID, ss.enrollID, ss.gradelvlID, ss.secID, sg.grade, sg.grade2, sg.grade3, sg.grade4
                FROM section_students ss
                LEFT JOIN student_grades sg ON ss.studID = sg.studID AND ss.enrollID = sg.enrollID AND ss.subjectID = sg.subjectID
                JOIN subjects s ON ss.subjectID = s.subjectID
                JOIN sections sc ON ss.secID = sc.secID
                WHERE ss.studID = :selectedStudID
                AND ss.ayName = :selectedAyName
                AND (ss.semID = :selectedSemID OR ss.semID IS NULL);

        ";
        $stmt = $conn->prepare($SQL);
        $stmt->bindParam(':selectedStudID', $studID, PDO::PARAM_INT);
        $stmt->bindParam(':selectedAyName', $ayName, PDO::PARAM_STR);
        $stmt->bindParam(':selectedSemID', $semID, PDO::PARAM_INT);
        $stmt->execute();

        // Debugging: check if subjects are found
        if ($stmt->rowCount() > 0) {
            echo '<option selected disabled>Select Subject</option>'; // Placeholder
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['subjectID'] . '" data-enrollid="' . $row['enrollID'] . '" 
                data-gradelvlid="' . $row['gradelvlID'] . '" 
                data-secid="' . $row['secID'] . '" 
                data-deptid="' . $row['deptID'] . '"
                data-grade="'.$row['grade']. '"
                data-grade2="'.$row['grade2'].'" 
                data-grade3="'.$row['grade3'].'" 
                data-grade4="'.$row['grade4'].'">' . $row['subjectname'] . '</option>';
            }
        } else {
            echo '<option disabled>No subjects found</option>';
        }
    } catch (PDOException $e) {
        echo '<option disabled>Error fetching subjects: ' . $e->getMessage() . '</option>';
    }
} else {
    echo '<option disabled>Invalid request</option>';
}
?>