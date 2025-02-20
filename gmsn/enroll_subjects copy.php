<?php
require_once "includes/config.php";

if (isset($_GET['studIDs'])) {
    $ayID = $_GET['ayID'];
    $gradelvlID = $_GET['gradelvlID'];
    $secID = $_GET['secID'];
    $ayName = $_GET['ayName'];
    $deptID = $_GET['deptID'];

    // Check if it's a semester-based or non-semester section
    $isSemesterSection = ($deptID == 3);  // If deptID is 3, it's a semester-based section

    // If it's a semester-based section, get semID and programID from the query string
    if ($isSemesterSection) {
        $semID = $_GET['semID'];
        $programID = $_GET['programID'];
    }

    // Get the list of student IDs
    $studIDs = explode(',', $_GET['studIDs']); // Convert back to an array

    // Fetch all subjects based on the section type
    $subjects = [];
    try {
        if ($isSemesterSection) {
            // Fetch subjects for semester-based section
            $query = "SELECT subjectID FROM curriculum 
                      WHERE semID = :semID AND gradelvlID = :gradelvlID AND programID = :programID";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
            $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
        } else {
            // Fetch subjects for non-semester-based section (no semID, no programID)
            $query = "SELECT c.subjectID 
                    FROM curriculum c
                    JOIN subject_grade_levels sgl ON c.curriculumID = sgl.curriculumID
                    WHERE sgl.gradelvlID = :gradelvlID;
                    ";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
        }
        $stmt->execute();
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        echo "Error fetching subjects: " . $e->getMessage();
    }

    // Enroll each student in each subject
    foreach ($studIDs as $studID) {
        foreach ($subjects as $subject) {
            $subjectID = $subject['subjectID'];

            try {
                $query = "INSERT IGNORE INTO section_students 
                          (studID, subjectID, ayID, gradelvlID, secID, ayName 
                          " . ($isSemesterSection ? ", semID, programID" : "") . ") 
                          VALUES (:studID, :subjectID, :ayID, :gradelvlID, :secID, :ayName 
                          " . ($isSemesterSection ? ", :semID, :programID" : "") . ")";

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmt->bindParam(':ayID', $ayID, PDO::PARAM_INT);
                $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                $stmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);

                // Bind semID and programID if it's a semester-based section
                if ($isSemesterSection) {
                    $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                    $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
                }

                $stmt->execute();
            } catch (Throwable $e) {
                echo "Error enrolling student ID {$studID} in subject {$subjectID}: " . $e->getMessage();
            }
        }
    }

    // Redirect to the appropriate page after enrolling students
    if($isSemesterSection){
        header("Location: enrolled_students.php?secID=$secID&gradelvlID=$gradelvlID&ayID=$ayID&facultyID=$facultyID&programID=$programID&semID=$semID&deptID=$deptID");
    }else{
        header("Location: enrolled_students.php?secID=$secID&gradelvlID=$gradelvlID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID");
    }
}
?>
