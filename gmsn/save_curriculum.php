<?php
require_once('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deptID = isset($_POST['deptID']) ? (int)$_POST['deptID'] : 0;
    $programID = isset($_POST['program_id']) ? (int)$_POST['program_id'] : 0;
    $selSub = isset($_POST['selSub']) ? $_POST['selSub'] : [];

    try {
        if ($deptID == 2) {
            $selLevel = isset($_POST['selLevel']) ? $_POST['selLevel'] : [];

            if (count($selLevel) === 1) {
                // Add prereqID 0 if only one item is selected
                $selLevel[] = 1;
            }

            foreach ($selSub as $subjectID) {
                // Check if the curriculum already exists for this subject and program
                $queryFetchCurriculumID = "SELECT curriculumID FROM curriculum WHERE subjectID = :subjectID AND programID = :programID";
                $stmtFetchCurriculumID = $conn->prepare($queryFetchCurriculumID);
                $stmtFetchCurriculumID->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmtFetchCurriculumID->bindParam(':programID', $programID, PDO::PARAM_INT);
                $stmtFetchCurriculumID->execute();
                $resultCurriculumID = $stmtFetchCurriculumID->fetch(PDO::FETCH_ASSOC);

                if ($resultCurriculumID) {
                    $curriculumID = $resultCurriculumID['curriculumID'];

                    // Delete existing grade level entries for this curriculum
                    $sqlDelete = "DELETE FROM subject_grade_levels WHERE curriculumID = :curriculumID";
                    $stmtDelete = $conn->prepare($sqlDelete);
                    $stmtDelete->bindParam(':curriculumID', $curriculumID, PDO::PARAM_INT);
                    $stmtDelete->execute();
                } else {
                    // Insert new curriculum entry if it doesn't exist
                    $sqlInsertCurriculum = "INSERT INTO curriculum (subjectID, programID) VALUES (:subjectID, :programID)";
                    $stmtInsertCurriculum = $conn->prepare($sqlInsertCurriculum);
                    $stmtInsertCurriculum->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                    $stmtInsertCurriculum->bindParam(':programID', $programID, PDO::PARAM_INT);
                    $stmtInsertCurriculum->execute();
                    $curriculumID = $conn->lastInsertId(); // Get the last inserted ID
                }

                // Insert new grade level entries for the selected curriculum
                foreach ($selLevel as $gradelvlID) {
                    $sqlInsertLevel = "INSERT INTO subject_grade_levels (curriculumID, gradelvlID) VALUES (:curriculumID, :gradelvlID)";
                    $stmtInsertLevel = $conn->prepare($sqlInsertLevel);
                    $stmtInsertLevel->bindParam(':curriculumID', $curriculumID, PDO::PARAM_INT);
                    $stmtInsertLevel->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                    $stmtInsertLevel->execute();
                }
            }

            $redirectUrl = "curriculumJHS.php?program_id=" . urlencode($programID) . "&deptID=" . urlencode($deptID);
            header("Location: " . $redirectUrl);
            exit();
        } else {
            // Handle for deptID != 2
            $selLevel = isset($_POST['selLevel']) ? (int)$_POST['selLevel'] : null;
            $selType = isset($_POST['selType']) ? (int)$_POST['selType'] : null;
            $selTerm = isset($_POST['selTerm']) ? (int)$_POST['selTerm'] : null;
            $selPre = isset($_POST['selPre']) ? $_POST['selPre'] : [];

            if (count($selPre) === 1) {
                // Add prereqID 0 if only one item is selected
                $selPre[] = 1;
            }

            foreach ($selSub as $subjectID) {
                // Check if the curriculum already exists for this subject and program
                $queryFetchCurriculumID = "SELECT curriculumID FROM curriculum WHERE subjectID = :subjectID AND programID = :programID";
                $stmtFetchCurriculumID = $conn->prepare($queryFetchCurriculumID);
                $stmtFetchCurriculumID->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmtFetchCurriculumID->bindParam(':programID', $programID, PDO::PARAM_INT);
                $stmtFetchCurriculumID->execute();
                $resultCurriculumID = $stmtFetchCurriculumID->fetch(PDO::FETCH_ASSOC);

                if ($resultCurriculumID) {
                    $curriculumID = $resultCurriculumID['curriculumID'];

                    // Update curriculum table
                    $sqlUpdate = "UPDATE curriculum SET typeID = :typeID, gradelvlID = :gradelvlID, semID = :semID WHERE subjectID = :subjectID AND programID = :programID";
                    $stmtUpdate = $conn->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':typeID', $selType, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':gradelvlID', $selLevel, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':semID', $selTerm, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':programID', $programID, PDO::PARAM_INT);
                    $stmtUpdate->execute();
                } else {
                    // Insert new curriculum entry if it doesn't exist
                    $sqlInsert = "INSERT INTO curriculum (subjectID, typeID, gradelvlID, semID, programID) VALUES (:subjectID, :typeID, :gradelvlID, :semID, :programID)";
                    $stmtInsert = $conn->prepare($sqlInsert);
                    $stmtInsert->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':typeID', $selType, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':gradelvlID', $selLevel, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':semID', $selTerm, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':programID', $programID, PDO::PARAM_INT);
                    $stmtInsert->execute();
                    $curriculumID = $conn->lastInsertId(); // Get the last inserted ID
                }

                // Insert prerequisites for the current curriculum entry
                foreach ($selPre as $prereqID) {
                    $sqlInsertPre = "INSERT INTO curriculum_prerequisites (curriculumID, prereqID) VALUES (:curriculumID, :prereqID)";
                    $stmtInsertPre = $conn->prepare($sqlInsertPre);
                    $stmtInsertPre->bindParam(':curriculumID', $curriculumID, PDO::PARAM_INT);
                    $stmtInsertPre->bindParam(':prereqID', $prereqID, PDO::PARAM_INT);
                    $stmtInsertPre->execute();
                }
            }
        }

        header("Location: curriculum.php?program_id=$programID&deptID=$deptID");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
}
?>
