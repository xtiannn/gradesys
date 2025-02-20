<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../logout.php');
    exit();
}

require_once "../includes/config.php";

$facultyNum = $_SESSION['userID'] ?? 0;

try {
    $sqlFac = "SELECT facultyID FROM faculty WHERE facultyNum = :facultyNum";
    $stmtFac = $conn->prepare($sqlFac);
    $stmtFac->bindParam(':facultyNum', $facultyNum, PDO::PARAM_STR);
    $stmtFac->execute();

    $result = $stmtFac->fetch(PDO::FETCH_ASSOC);
    $facultyID = $result['facultyID'];

    if ($facultyID) {
        try {
            // Fetch all subject IDs assigned to the faculty
            $sqlSub = "SELECT subjectID FROM facultyAssign WHERE facultyID = :facultyID";
            $stmtSub = $conn->prepare($sqlSub);
            $stmtSub->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
            $stmtSub->execute();

            $subjects = $stmtSub->fetchAll(PDO::FETCH_ASSOC);

            // Initialize an empty array to hold results for all subjects
            $results = [];

            
            foreach ($subjects as $subject) {
                $subjectID = $subject['subjectID'];

                // Fetch ungraded students for the current subject
                $stmt = $conn->prepare("
                    SELECT s.subjectname, COUNT(*) AS ungraded_count, ss.subjectID
                    FROM section_students ss
                    JOIN subjects s ON s.subjectID = ss.subjectID
                    LEFT JOIN student_grades sg ON ss.studID = sg.studID AND ss.subjectID = sg.subjectID
                    WHERE ss.subjectID = :subjectID
                    AND (sg.grade IS NULL OR sg.grade = '')
                    GROUP BY s.subjectname, ss.subjectID;
                ");

                $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $results[] = $result;
                }
            }

            // Return the results as JSON
            header('Content-Type: application/json');
            echo json_encode($results);

        } catch (PDOException $e) {
            echo 'ERROR FETCHING Subjects: ' . $e->getMessage();
        }
    }

} catch (\Throwable $e) {
    echo 'ERROR FETCHING facultyID: ' . $e->getMessage();
}
?>
