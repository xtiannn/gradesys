
<?php
require_once '../includes/config.php'; 

if (isset($_POST['studID'], $_POST['subjectID'])) {
    $studID = $_POST['studID'];
    $subjectID = $_POST['subjectID'];

    try {
        // Query to get the existing grade data
        $sql = "SELECT * FROM student_grades WHERE studID = :studID AND subjectID = :subjectID";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':studID' => $studID,
            ':subjectID' => $subjectID
        ]);

        $gradeData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($gradeData) {
            echo json_encode($gradeData);
        } else {
            echo json_encode(['firstInput' => '', 'secondInput' => '']); // No grades found
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching grades']);
    }
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
?>
