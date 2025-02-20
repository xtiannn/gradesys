<?php
// save_grade_permission.php

require_once 'includes/config.php'; // Include the database configuration file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $firstSwitch = isset($_POST['firstSwitch']) ? $_POST['firstSwitch'] : null;
    $secondSwitch = isset($_POST['secondSwitch']) ? $_POST['secondSwitch'] : null;

    if ($firstSwitch !== null && $secondSwitch !== null) {
        try {
            $sql = "UPDATE gradepermission SET _first = ?, _second = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$firstSwitch, $secondSwitch]);
            echo "Grade permissions updated successfully.";
            $previousPage = $_SERVER['HTTP_REFERER'];
            header("Location: $previousPage");
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid input.";
    }
} else {
    echo "Invalid request method.";
}
?>
