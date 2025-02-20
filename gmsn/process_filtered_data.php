<?php
require_once("includes/config.php");

$studID = $_GET['studID'];
$studName = urlencode($_GET['studName']);
$selected_gradelvlID = isset($_GET['gradelvlID']) ? $_GET['gradelvlID'] : '';
$selected_semID = isset($_GET['semID']) ? $_GET['semID'] : '';

try {
    // Fetch filtered data with necessary conditions
    $query = "SELECT ss.*, 
                (SELECT subjectcode FROM subjects s WHERE s.subjectID = ss.subjectID) AS code,
                (SELECT subjectname FROM subjects s WHERE s.subjectID = ss.subjectID) AS subject
              FROM section_students ss 
              WHERE subjectID != 0 AND grade is not null AND grade2 is not null AND fgrade is not null";

    if (!empty($selected_gradelvlID)) {
        $query .= " AND ss.gradelvlID = :gradelvlID";
    }
    if (!empty($selected_semID)) {
        $query .= " AND ss.semID = :semID";
    }

    $stmt = $conn->prepare($query);

    if (!empty($selected_gradelvlID)) {
        $stmt->bindParam(':gradelvlID', $selected_gradelvlID);
    }
    if (!empty($selected_semID)) {
        $stmt->bindParam(':semID', $selected_semID);
    }

    $stmt->execute();
    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize variables to calculate averages
    $subjectCounts = []; // Array to count occurrences of each subject
    $subjectSum = []; // Array to store the sum of grades for each subject

    // Calculate sums and counts for each subject
    foreach ($curriculum as $row) {
        $subjectID = $row['subjectID'];
        $fgrade = $row['fgrade'];

        // Initialize sum and count if not set
        if (!isset($subjectSum[$subjectID])) {
            $subjectSum[$subjectID] = 0;
            $subjectCounts[$subjectID] = 0;
        }

        // Add grade to sum and increment count for the subject
        if ($fgrade !== null) {
            $subjectSum[$subjectID] += $fgrade;
            $subjectCounts[$subjectID]++;
        }
    }

    // Calculate averages for each subject
    $averages = [];
    foreach ($subjectSum as $subjectID => $sum) {
        $count = $subjectCounts[$subjectID];
        if ($count > 0) {
            $average = $sum / $count;
            $averages[$subjectID] = $average;
        } else {
            $averages[$subjectID] = null; // Handle division by zero case if needed
        }
    }

    // Calculate general average (average of all subjects)
    $generalAverage = count($averages) > 0 ? array_sum($averages) / count($averages) : null;

    // Check if there's an existing record for this combination of studID, gradelvlID, and semID
    $queryCheck = "SELECT * FROM student_grades WHERE studID = :studID AND gradelvlID = :gradelvlID AND semID = :semID";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bindParam(':studID', $studID);
    $stmtCheck->bindParam(':gradelvlID', $selected_gradelvlID);
    $stmtCheck->bindParam(':semID', $selected_semID);
    $stmtCheck->execute();
    $existingRecord = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($existingRecord) {
        // Update existing record
        $queryUpdate = "UPDATE student_grades SET genave = :genave WHERE studID = :studID AND gradelvlID = :gradelvlID AND semID = :semID";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bindParam(':genave', $generalAverage);
        $stmtUpdate->bindParam(':studID', $studID);
        $stmtUpdate->bindParam(':gradelvlID', $selected_gradelvlID);
        $stmtUpdate->bindParam(':semID', $selected_semID);
        $stmtUpdate->execute();
    } else {
        // Insert new record
        $queryInsert = "INSERT INTO student_grades (studID, genave, gradelvlID, semID) 
                        VALUES (:studID, :genave, :gradelvlID, :semID)";
        $stmtInsert = $conn->prepare($queryInsert);
        $stmtInsert->bindParam(':studID', $studID);
        $stmtInsert->bindParam(':genave', $generalAverage);
        $stmtInsert->bindParam(':gradelvlID', $selected_gradelvlID);
        $stmtInsert->bindParam(':semID', $selected_semID);
        $stmtInsert->execute();
    }

    // Convert averages to JSON format
    $averages_json = json_encode($averages);

    // Redirect to students_subj.php with studID and studName
    header("Location: students_subj.php?studID={$studID}&studName={$studName}&gradelvlID={$selected_gradelvlID}&semID={$selected_semID}");
    exit;
} catch (PDOException $e) {
    // Handle PDO exceptions
    echo "Error: " . $e->getMessage();
}
?>
