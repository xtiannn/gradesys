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

    if (empty($code) || empty($desc)) {
        $response['message'] = "Subject code and name cannot be empty.";
    } else {
        try {
            $conn->beginTransaction();

            // Update the subjects table
            $query = "UPDATE subjects SET subjectcode = ?, subjectname = ? WHERE subjectID = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$code, $desc, $subjectID]);

            // Delete existing entries in the subject_program table
            $query2 = "DELETE FROM subject_program WHERE subjectID = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->execute([$subjectID]);

            // Insert new entries into the subject_program table
            $query3 = "INSERT INTO subject_program (subjectID, programID) VALUES (?, ?)";
            $stmt3 = $conn->prepare($query3);
            foreach ($progs as $programID) {
                $stmt3->execute([$subjectID, $programID]);
            }

            // Clear existing programID values in the curriculum table for this subjectID
            $queryUpdateCurriculumDelete = "DELETE FROM curriculum WHERE subjectID = ?";
            $stmtUpdateCurriculumDelete = $conn->prepare($queryUpdateCurriculumDelete);
            $stmtUpdateCurriculumDelete->execute([$subjectID]);

            // Insert new programID values into the curriculum table
            $queryUpdateCurriculumInsert = "INSERT INTO curriculum (subjectID, programID) VALUES (?, ?)";
            $stmtUpdateCurriculumInsert = $conn->prepare($queryUpdateCurriculumInsert);
            foreach ($progs as $programID) {
                $stmtUpdateCurriculumInsert->execute([$subjectID, $programID]);
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
