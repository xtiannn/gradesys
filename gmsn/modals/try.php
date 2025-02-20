<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enrollBtn'])) {
    // Check if any students are selected
    if (!empty($_POST['selStud'])) {
        try {
            // Include database connection
            require_once("../includes/config.php");

            // Start a transaction
            $conn->beginTransaction();

            // Prepare SQL statement to insert selected students into the database
            $sql = "INSERT INTO enrolled_student (studID) VALUES (:studID)";
            $stmt = $conn->prepare($sql);

            // Bind parameters and execute the statement for each selected student
            foreach ($_POST['selStud'] as $studID) {
                $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Commit the transaction
            $conn->commit();

            // Redirect to the previous page with a success message
          
            exit();
        } catch (PDOException $e) {
            // Roll back the transaction in case of error
            $conn->rollBack();

            // Log the error
            error_log("Database error: " . $e->getMessage());

            // Redirect to the previous page with an error message
            header("Location: ../previous_page.php?error=database_error");
            exit();
        } finally {
            // Close the database connection
            $conn = null;
        }
    } else {
        // Redirect to the previous page with an error message if no students are selected
        header("Location: ../previous_page.php?error=no_students_selected");
        exit();
    }
} else {
    // Redirect to the previous page with an error message if the form is not submitted correctly
    header("Location: ../previous_page.php?error=invalid_request");
    exit();
}

?>
