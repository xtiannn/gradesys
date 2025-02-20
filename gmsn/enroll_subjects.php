<?php
require_once "includes/config.php";

// Check if 'studIDs' is set in the query parameters
if (isset($_GET['studIDs'])) {
    // Sanitize the incoming query parameters
    $ayID = filter_input(INPUT_GET, 'ayID', FILTER_SANITIZE_NUMBER_INT);
    $gradelvlID = filter_input(INPUT_GET, 'gradelvlID', FILTER_SANITIZE_NUMBER_INT);
    $secID = filter_input(INPUT_GET, 'secID', FILTER_SANITIZE_NUMBER_INT);
    $ayName = filter_input(INPUT_GET, 'ayName', FILTER_SANITIZE_STRING);
    $deptID = filter_input(INPUT_GET, 'deptID', FILTER_SANITIZE_NUMBER_INT);

    
    // Get facultyID from session or request (depending on how it's managed)
    $facultyID = isset($_SESSION['facultyID']) ? $_SESSION['facultyID'] : filter_input(INPUT_GET, 'facultyID', FILTER_SANITIZE_NUMBER_INT);

    // Check if it's a semester-based or non-semester section
    $isSemesterSection = ($deptID == 3);  // If deptID is 3, it's a semester-based section

    // If it's a semester-based section, get semID and programID from the query string
    if ($isSemesterSection) {
        $semID = filter_input(INPUT_GET, 'semID', FILTER_SANITIZE_NUMBER_INT) ?? NULL;
        $programID = filter_input(INPUT_GET, 'programID', FILTER_SANITIZE_NUMBER_INT) ?? NULL;
    }else{
        $programID = 0;
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
                    WHERE sgl.gradelvlID = :gradelvlID;";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
        }
        $stmt->execute();
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        echo "Error fetching subjects: " . $e->getMessage();
    }

    // Insert into section_students table
    try {
        foreach ($studIDs as $studID) {
            foreach ($subjects as $subject) {
                $subjectID = $subject['subjectID'];

                // Insert into section_students table
                $query = "INSERT IGNORE INTO section_students 
                          (studID, subjectID, ayID, gradelvlID, secID, ayName, programID" 
                          . ($isSemesterSection ? ", semID" : "") . ") 
                          VALUES (:studID, :subjectID, :ayID, :gradelvlID, :secID, :ayName, :programID" 
                          . ($isSemesterSection ? ", :semID" : "") . ")";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmt->bindParam(':ayID', $ayID, PDO::PARAM_INT);
                $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                $stmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);
                $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);


                if ($isSemesterSection) {
                    $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                }

                $stmt->execute();
            }
        }
    } catch (Throwable $e) {
        echo "Error enrolling students in section_students: " . $e->getMessage();
    }

    // Insert into student_grades table for each student and subject pair
    try {
        foreach ($studIDs as $studID) {
            foreach ($subjects as $subject) {
                $subjectID = $subject['subjectID'];

                // Simplified Insert Query for student_grades table
                $query_grades = "INSERT IGNORE INTO student_grades (studID, subjectID, gradelvlID, secID, ayName" 
                                . ($isSemesterSection ? ", semID" : "") . ") 
                                VALUES (:studID, :subjectID, :gradelvlID, :secID, :ayName" 
                                . ($isSemesterSection ? ", :semID" : "") . ")";
                $stmt_grades = $conn->prepare($query_grades);

                // Bind parameters for studID and subjectID
                $stmt_grades->bindParam(':studID', $studID, PDO::PARAM_INT);
                $stmt_grades->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmt_grades->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                $stmt_grades->bindParam(':secID', $secID, PDO::PARAM_INT);
                $stmt_grades->bindParam(':ayName', $ayName, PDO::PARAM_STR);

                if ($isSemesterSection) {
                    $stmt_grades->bindParam(':semID', $semID, PDO::PARAM_INT);
                }


                // Execute the query for each student and subject
                try {
                    $stmt_grades->execute();
                } catch (Throwable $e) {
                    echo "Error inserting student grade: " . $e->getMessage();
                }
            }
        }
    } catch (Throwable $e) {
        echo "Error enrolling students in student_grades: " . $e->getMessage();
    }

    // Redirect to the appropriate page after enrolling students
    if ($isSemesterSection) {
        header("Location: enrolled_students.php?secID=$secID&gradelvlID=$gradelvlID&ayID=$ayID&facultyID=$facultyID&programID=$programID&semID=$semID&deptID=$deptID");
    } else {
        header("Location: enrolled_students.php?secID=$secID&gradelvlID=$gradelvlID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID");
    }
}
?>
