<?php
require_once("../includes/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        global $conn;

        // Begin transaction
        $conn->beginTransaction();

        // Process each grade entry
        if (isset($_POST['grades']) && is_array($_POST['grades'])) {
            foreach ($_POST['grades'] as $gradeData) {
                $id = $gradeData['id'] ?? null;
                $grade = $gradeData['grade'] ?? null;
                $grade2 = $gradeData['grade2'] ?? null;

                // Compute the sum of grades
                $sum = ($grade ? (float)$grade : 0) + ($grade2 ? (float)$grade2 : 0);
                
                // Compute final grade as the average of grade and grade2
                $fgrade = ($sum / 2);

                if ($id) {
                    // Update record if exists
                    $sql = "UPDATE student_grades SET grade = :grade, grade2 = :grade2, fgrade = :fgrade WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        'id' => $id,
                        'grade' => $grade,
                        'grade2' => $grade2,
                        'fgrade' => $fgrade
                    ]);
                } else {
                    // Insert new record if needed (though ID should always be present)
                    $sql = "INSERT INTO student_grades (id, grade, grade2, fgrade) VALUES (:id, :grade, :grade2, :fgrade)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        'id' => $id,
                        'grade' => $grade,
                        'grade2' => $grade2,
                        'fgrade' => $fgrade
                    ]);
                }
            }

            // Commit transaction
            $conn->commit();

            // Redirect to the same page with a success message
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'stud_subject.php'; // Fallback to a default URL if no referrer
            header("Location: $redirectUrl?status=success");
            exit;
        } else {
            echo 'No data to update';
        }

    } catch (PDOException $e) {
        // Rollback transaction on error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method";
}
?>
