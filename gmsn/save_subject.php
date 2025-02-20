<?php
require_once("includes/config.php");

$response = []; 

if (isset($_POST['saveSubjectBtn'])) {
    $code = $_POST['txtSubjectCode'] ?? '';
    $desc = ucwords(strtolower($_POST['txtSubjectName'])) ?? '';
    $progs = $_POST['selProg'] ?? ''; 

    // Check if fields are empty
    if (empty($code) || empty($desc) || empty($progs)) {
        $response['status'] = 'error';
        $response['message'] = "Subject code and name cannot be empty.";
    }

    try {
        $conn->beginTransaction();

        // Insert the subject into the subjects table
        $query = "INSERT INTO subjects (subjectcode, subjectname) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$code, $desc]);

        // Retrieve the last inserted subject ID
        $subjectID = $conn->lastInsertId();

        // Insert into the subject_program table for each selected program
        foreach ($progs as $programID) {
            $query3 = "INSERT INTO subject_program (subjectID, programID) VALUES (?, ?)";
            $stmt3 = $conn->prepare($query3);
            $stmt3->execute([$subjectID, $programID]);

            // Insert the subject ID into the curriculum table
            $query2 = "INSERT INTO curriculum (subjectID, programID) VALUES (?, ?)";
            $stmt2 = $conn->prepare($query2);
            $stmt2->execute([$subjectID, $programID]);
        }

        $conn->commit();
        $response['status'] = 'success';
        $response['message'] = "Subject has been added successfully";

    } catch (PDOException $e) {
        $conn->rollBack();
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} elseif (isset($_POST['updateSubjectBtn'])) {
    $code = $_POST['txtSubjectCode'];
    $desc = ucwords(strtolower($_POST['txtSubjectName']));
    $progs = $_POST['selProg'];
    $subjectID = $_POST['subjectID'];
    $status = $_POST['status'];

    if (empty($code) || empty($desc)) {
        $response['message'] = "Subject code and name cannot be empty.";
    } else {
        try {
            $conn->beginTransaction();

            // Step 1: Get the current values from the database for comparison
            $queryGetSubject = "SELECT subjectcode, subjectname, isActive FROM subjects WHERE subjectID = ?";
            $stmtGetSubject = $conn->prepare($queryGetSubject);
            $stmtGetSubject->execute([$subjectID]);
            $currentSubject = $stmtGetSubject->fetch(PDO::FETCH_ASSOC);

            // Check if subject code or description has changed
            $subjectCodeChanged = ($code !== $currentSubject['subjectcode']);
            $subjectDescChanged = ($desc !== $currentSubject['subjectname']);
            $statusChanged = ($status !== $currentSubject['isActive']);

            // Step 2: Update only the changed fields in the subjects table
            if ($subjectCodeChanged || $subjectDescChanged || $statusChanged) {
                $fieldsToUpdate = [];
                $params = [];

                // Add subject code update if changed
                if ($subjectCodeChanged) {
                    $fieldsToUpdate[] = "subjectcode = ?";
                    $params[] = $code;
                }

                // Add subject name update if changed
                if ($subjectDescChanged) {
                    $fieldsToUpdate[] = "subjectname = ?";
                    $params[] = $desc;
                }

                // Add status update if changed
                if ($statusChanged) {
                    $fieldsToUpdate[] = "isActive = ?";
                    $params[] = $status;
                }

                // If any field is updated, perform the update query
                if (!empty($fieldsToUpdate)) {
                    $query = "UPDATE subjects SET " . implode(", ", $fieldsToUpdate) . " WHERE subjectID = ?";
                    $params[] = $subjectID;
                    $stmt = $conn->prepare($query);
                    $stmt->execute($params);
                }
            }

            // Step 3: Only update subject_program and curriculum if programs have changed
            if (!empty($progs)) {
                // Ensure $progs is an array (it's coming from POST, so it may be a string)
                if (is_string($progs)) {
                    $progs = explode(',', $progs); // Split comma-separated string into an array
                }

                // Get the current programIDs from the subject_program table
                $queryGetPrograms = "SELECT programID FROM subject_program WHERE subjectID = ?";
                $stmtGetPrograms = $conn->prepare($queryGetPrograms);
                $stmtGetPrograms->execute([$subjectID]);
                $currentPrograms = $stmtGetPrograms->fetchAll(PDO::FETCH_COLUMN);

                // Sort both arrays before comparison (to ensure the order doesn't affect the result)
                sort($progs);
                sort($currentPrograms);

                // Check if programs are different by comparing sorted arrays
                if ($progs !== $currentPrograms) {
                    // Programs have changed, so update the subject_program and curriculum tables

                    // Step 3.1: Delete existing entries in the subject_program table
                    $queryDeletePrograms = "DELETE FROM subject_program WHERE subjectID = ?";
                    $stmtDeletePrograms = $conn->prepare($queryDeletePrograms);
                    $stmtDeletePrograms->execute([$subjectID]);

                    // Step 3.2: Insert new entries into the subject_program table
                    $queryInsertPrograms = "INSERT INTO subject_program (subjectID, programID) VALUES (?, ?)";
                    $stmtInsertPrograms = $conn->prepare($queryInsertPrograms);
                    foreach ($progs as $programID) {
                        $stmtInsertPrograms->execute([$subjectID, $programID]);
                    }

                    // Step 3.3: Clear existing programID values in the curriculum table for this subjectID
                    $queryDeleteCurriculum = "DELETE FROM curriculum WHERE subjectID = ?";
                    $stmtDeleteCurriculum = $conn->prepare($queryDeleteCurriculum);
                    $stmtDeleteCurriculum->execute([$subjectID]);

                    // Step 3.4: Insert new programID values into the curriculum table
                    $queryInsertCurriculum = "INSERT INTO curriculum (subjectID, programID) VALUES (?, ?)";
                    $stmtInsertCurriculum = $conn->prepare($queryInsertCurriculum);
                    foreach ($progs as $programID) {
                        $stmtInsertCurriculum->execute([$subjectID, $programID]);
                    }
                }
            }

            $conn->commit();

            $response['status'] = 'success';
            $response['message'] = "Subject Updated Successfully";
        } catch (PDOException $e) {
            $conn->rollBack();
            $response['message'] = 'Error: ' . $e->getMessage();
        }
    }
}


echo json_encode($response);
?>
