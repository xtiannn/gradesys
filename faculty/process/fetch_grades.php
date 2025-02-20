<?php
require_once "../includes/config.php";

$studID = isset($_POST['student']) ? $_POST['student'] : '';
$subjectID = isset($_POST['subject']) ? $_POST['subject'] : '';

try {
    $query = "SELECT g.grade, s.lname, s.fname, s.mname, sb.subjectname
              FROM student_grades g
              JOIN students s ON g.studID = s.studID
              JOIN subjects sb ON g.subjectID = sb.subjectID
              WHERE (:studID = '' OR g.studID = :studID)
              AND (:subjectID = '' OR g.subjectID = :subjectID)";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':studID', $studID, PDO::PARAM_STR);
    $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_STR);
    $stmt->execute();
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($grades as $grade) {
        echo "<tr>
                <td>{$grade['lname']}, {$grade['fname']} {$grade['mname']}</td>
                <td>{$grade['subjectname']}</td>
                <td>{$grade['grade']}</td>
              </tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
