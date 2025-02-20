<?php
require '../../vendor/autoload.php';  // Path to PhpSpreadsheet
require '../includes/config.php';  // Ensure $conn (PDO instance) is defined in config.php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileTmpPath = $_FILES['file']['tmp_name'];  // Temporary file path
    $password = $_POST['password'] ?? '';  // Optional password input

    // Increase memory limit
    ini_set('memory_limit', '1024M');

    try {
        // Load the uploaded Excel file
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);  // Only read data to reduce memory usage
        if ($password) {
            $reader->setPassword($password);  // Use password if provided
        }

        // Load the spreadsheet
        $spreadsheet = $reader->load($fileTmpPath);

        // Get the first (and only) sheet
        $sheet = $spreadsheet->getSheet(0);

        // Prepare an array to hold the student data
        $studentData = [];

        // Iterate over rows starting from row 6
        foreach ($sheet->getRowIterator(6) as $row) {
            // Get the concatenated name from the appropriate cell (assuming column A has the name)
            $fullName = $sheet->getCell('A' . $row->getRowIndex())->getValue(); // Assuming concatenated name is in column A

            // Debugging: Output the full name read from the Excel file
            echo "Full Name Read: " . htmlspecialchars($fullName) . "<br>";

            // Ensure the full name is a string before processing
            if (is_string($fullName)) {
                // Split the name into parts
                if (preg_match('/^(.*?),\s*(.*?)\s*(.*?)$/', $fullName, $matches)) {
                    $lname = trim($matches[1]); // Last Name
                    $fname = trim($matches[2]); // First Name
                    $mname = trim($matches[3]); // Middle Name (if exists)
                } else {
                    // Handle case where name format doesn't match expected pattern
                    $lname = $fname = $mname = '';
                    // Debugging: Output if the name format does not match
                    echo "Name format does not match expected pattern.<br>";
                }
            } else {
                // Debugging: Output if the full name is not a string
                echo "Value is not a string: " . htmlspecialchars($fullName) . "<br>";
                $lname = $fname = $mname = ''; // Reset names
            }

            // Store chopped names in studentData
            $studentData[] = [$lname, $fname, $mname];  // Only store last, first, middle names
        }

        // Clean up memory
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        // Display the student data
        if (!empty($studentData)) {
            echo '<h2>Student Data from Excel</h2>';
            echo '<table border="1">';
            echo '<tr><th>Last Name</th><th>First Name</th><th>Middle Name</th></tr>'; // Adjust headings as needed
            foreach ($studentData as $data) {
                echo '<tr>';
                foreach ($data as $value) {
                    echo '<td>' . htmlspecialchars($value) . '</td>';  // Display each cell value safely
                }
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'No student data found.';
        }

    } catch (Exception $e) {
        echo 'Error loading file: ', $e->getMessage();
    }
} else {
    echo 'No file uploaded.';
}
