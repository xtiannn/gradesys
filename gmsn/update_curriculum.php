<?php 

require_once("includes/config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['updateCurriculumBtn'])) {
        $programID = $_POST['programID'];
        $curriculumID = $_POST['curriculumID'];
        $selSub = $_POST['selSub'];
        $selType = $_POST['selType'];
        $selLevel = $_POST['selLevel'];
        $selTerm = $_POST['selTerm'];
        $deptID = $_POST['deptID'];
        $selPre = !empty($_POST['selPre']) ? $_POST['selPre'] : []; 

        if (count($selPre) === 1) {
            // Add prereqID 0 if only one item is selected
            $selPre[] = 1;
        }

        try {
            $sql = "UPDATE curriculum 
                    SET subjectID = :subjectID, typeID = :subgrpID, gradelvlID = :gradelvlID, semID = :semID
                    WHERE curriculumID = :curriculumID";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':subjectID', $selSub, PDO::PARAM_INT);
            $stmt->bindParam(':subgrpID', $selType, PDO::PARAM_INT);
            $stmt->bindParam(':gradelvlID', $selLevel, PDO::PARAM_INT);
            $stmt->bindParam(':semID', $selTerm, PDO::PARAM_INT);
            $stmt->bindParam(':curriculumID', $curriculumID, PDO::PARAM_INT);
            $stmt->execute();

            // Delete existing prerequisites
            $sqlDelete = "DELETE FROM curriculum_prerequisites WHERE curriculumID = :curriculumID";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bindParam(':curriculumID', $curriculumID, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insert new prerequisites
            foreach ($selPre as $prereqID) {
                $sqlInsert = "INSERT INTO curriculum_prerequisites (curriculumID, prereqID) VALUES (:curriculumID, :prereqID)";
                $stmtInsert = $conn->prepare($sqlInsert);
                $stmtInsert->bindParam(':curriculumID', $curriculumID, PDO::PARAM_INT);
                $stmtInsert->bindParam(':prereqID', $prereqID, PDO::PARAM_INT);
                $stmtInsert->execute();
            }

            header("Location: curriculum.php?program_id=$programID&deptID=$deptID");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }
    elseif(isset($_POST['saveCurriculumBtn'])) {
        // my existing code for saving new curriculum can go here
    }
}
?>