<?php
require '../../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once("../includes/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the POST data
    $programID = $_POST['programID'] ?? NULL;
    $gradelvlID = $_POST['gradelvlID'] ?? NULL;
    $secID = $_POST['secID'] ?? NULL;
    $semID = $_POST['semID'] ?? NULL;
    $facultyID = $_POST['facultyID'] ?? NULL;
    $studID = $_POST['studID'] ?? NULL;
    $subjectID = $_POST['subjectID'] ?? NULL;

    // SQL Query
    $sql = "
        SELECT ss.*, 
            s.subjectcode as code,
            s.subjectname as subject,
            st.lrn,
            CONCAT(st.lname, ', ', st.fname, ' ', st.mname) AS studname,
            sg.grade,
            sg.grade2,
            sg.grade3,
            sg.grade4,
            sg.fgrade
        FROM section_students ss
        JOIN subjects s ON s.subjectID = ss.subjectID
        JOIN students st ON st.studID = ss.studID
        LEFT JOIN student_grades sg ON sg.studID = ss.studID AND sg.subjectID = ss.subjectID AND sg.secID = ss.secID
        WHERE ss.studID = :studID 
            AND ss.gradelvlID = :gradelvlID 
            AND ss.subjectID != :subjectID
            AND ss.secID = :secID
        ORDER BY s.subjectname ASC
    ";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':studID', $studID); 
    $stmt->bindParam(':gradelvlID', $gradelvlID);
    $stmt->bindParam(':subjectID', $subjectID); 
    $stmt->bindParam(':secID', $secID);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are results
    if ($results) {
        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header row
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'A.Y.');
        $sheet->setCellValue('C1', 'Subject Code');
        $sheet->setCellValue('D1', 'Subject Name');
        $sheet->setCellValue('E1', 'Q1');
        $sheet->setCellValue('F1', 'Q2');
        $sheet->setCellValue('G1', 'Q3');
        $sheet->setCellValue('H1', 'Q4');
        $sheet->setCellValue('I1', 'Final Grade');

        // Set column widths for D (Subject Name) and F (Q2)
        $sheet->getColumnDimension('B')->setWidth(20); 
        $sheet->getColumnDimension('C')->setWidth(30); 
        $sheet->getColumnDimension('D')->setWidth(50); 
        $sheet->getColumnDimension('I')->setWidth(20);

        // Start populating data from row 2
        $rowNumber = 2;
        $count = 1;

        foreach ($results as $row) {
            $sheet->setCellValue('A' . $rowNumber, $count++);
            $sheet->setCellValue('B' . $rowNumber, htmlspecialchars($row['ayName'])); // Adjust if 'ayName' is a valid column
            $sheet->setCellValue('C' . $rowNumber, htmlspecialchars($row['code']));
            $sheet->setCellValue('D' . $rowNumber, htmlspecialchars($row['subject']));
            $sheet->setCellValue('E' . $rowNumber, htmlspecialchars($row['grade']));
            $sheet->setCellValue('F' . $rowNumber, htmlspecialchars($row['grade2']));
            $sheet->setCellValue('G' . $rowNumber, htmlspecialchars($row['grade3']));
            $sheet->setCellValue('H' . $rowNumber, htmlspecialchars($row['grade4']));
            $sheet->setCellValue('I' . $rowNumber, htmlspecialchars($row['fgrade']));
            $rowNumber++;
        }

        // Create a writer instance to save the spreadsheet to Excel
        $writer = new Xlsx($spreadsheet);

        // Set the filename for download
        $filename = 'student_grades_export_' . time() . '.xlsx';

        // Set headers to prompt for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Save the Excel file to the output buffer
        $writer->save('php://output');
    } else {
        echo "No results found.";
    }
}
?>
