<?php 

require_once("includes/config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $programID = $_POST['programID'];
        $curriculumID = $_POST['curriculumID'];
        $selSub = $_POST['selSub'];
        $deptID = $_POST['deptID'];
        $selLevel = !empty($_POST['selLevel']) ? $_POST['selLevel'] : []; 

        if (count($selLevel) === 1) {
            // Add prereqID 0 if only one item is selected
            $selLevel[] = 1;
        }

        try {
            // Delete existing gradelevels
            $sqlDelete = "DELETE FROM subject_grade_levels WHERE curriculumID = :curriculumID";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bindParam(':curriculumID', $curriculumID, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insert new gradelevels
            foreach ($selLevel as $gradelvlID) {
                $sqlInsert = "INSERT INTO subject_grade_levels (curriculumID, gradelvlID) VALUES (:curriculumID, :gradelvlID)";
                $stmtInsert = $conn->prepare($sqlInsert);
                $stmtInsert->bindParam(':curriculumID', $curriculumID, PDO::PARAM_INT);
                $stmtInsert->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                $stmtInsert->execute();
            }

            header("Location: curriculumJHS.php?program_id=$programID&deptID=$deptID");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            $conn = null;
        }
}
?>