<?php
// Include PhpSpreadsheet library
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once "../includes/config.php";

// Retrieve parameters for subjectID and secID from the URL (via GET)
$subjectID = isset($_GET['subjectID']) ? (int) $_GET['subjectID'] : 0;
$secID = isset($_GET['secID']) ? (int) $_GET['secID'] : 0;
$facultyID = isset($_GET['facultyID']) ? (int) $_GET['facultyID'] : 0;
$semID = isset($_GET['semID']) ? (int) $_GET['semID'] : 0;
$deptID = isset($_GET['deptID']) ? (int) $_GET['deptID'] : 0;
$programID = isset($_GET['programID']) ? (int) $_GET['programID'] : 0;
$activeSem = isset($_GET['activeSem']) ? (int) $_GET['activeSem'] : 0;

if ($subjectID === 0 || $secID === 0) {
    // If subjectID or secID is not set, return an error or stop execution
    echo "Error: Invalid subject or section.";
    exit;
}


// Retrieve parameters for subjectID and secID from the URL (via GET)
$subjectID = isset($_GET['subjectID']) ? (int) $_GET['subjectID'] : 0;
$secID = isset($_GET['secID']) ? (int) $_GET['secID'] : 0;

if ($subjectID === 0 || $secID === 0) {
    // If subjectID or secID is not set, return an error or stop execution
    echo "Error: Invalid subject or section.";
    exit;
}

// SQL query to retrieve student data
    $sqlStudents = "SELECT DISTINCT
        s.studID, ss.enrollID, sc.deptID, sc.ayName,
        CONCAT(s.lname, ', ', s.fname, ' ', s.mname) AS studName,
        CONCAT(f.lname, ', ', f.fname, ' ', f.mname) AS facultyName,
        s.lrn, sg.grade, sg.grade2, sg.grade3, sg.grade4, sg.fgrade, sm.semCode, sm.semID, sb.subjectname, sc.secName, sc.secID, 
        p.programcode, p.programname, p.programID,
        gl.gradelvlcode, gl.gradelvlID, ss.enrollID, ss.subjectID, ss.ayID, ss.semID, fa.facultyID
    FROM section_students ss
    JOIN students s ON ss.studID = s.studID
    LEFT JOIN semester sm ON ss.semID = sm.semID
    JOIN subjects sb ON ss.subjectID = sb.subjectID
    JOIN sections sc ON ss.secID = sc.secID
    LEFT JOIN programs p ON ss.programID = p.programID
    JOIN grade_level gl ON ss.gradelvlID = gl.gradelvlID
    JOIN facultyAssign fa ON fa.facultyID = :facultyID
    JOIN faculty f ON fa.facultyID = f.facultyID
    LEFT JOIN student_grades sg ON ss.studID = sg.studID AND ss.subjectID = sg.subjectID AND sc.secID = sg.secID
    WHERE ss.subjectID = :subjectID AND ss.secID = :secID
    ORDER BY studName ASC";

try {
    $stmtStudents = $conn->prepare($sqlStudents);
    $stmtStudents->bindParam(':facultyID', $facultyID, PDO::PARAM_INT); // Get secID from URL
    $stmtStudents->bindParam(':subjectID', $subjectID, PDO::PARAM_INT); // Get subjectID from URL
    $stmtStudents->bindParam(':secID', $secID, PDO::PARAM_INT); // Get secID from URL
    $stmtStudents->execute();
    $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);
} catch (\Throwable $e) {
    echo '<span>Error: ' . $e->getMessage() . '</span>';
    exit;
}

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Retrieve grade level, section name, and subject name
$gradeLevel = strtolower($students[0]['gradelvlcode']);
$sectionName = strtolower($students[0]['secName']);
$formattedGradeLevel = ucwords($gradeLevel);
$formattedSectionName = ucwords($sectionName);


$facultyName = strtolower($students[0]['facultyName']); 
$formattedFacultyName = ucwords($facultyName);

// Set row 1-4 header content (Subject Teacher, Program, Grade and Section, Subject)
if ($semID != 0){
    $sheet->setCellValue('B1', 'Faculty:');
    $sheet->setCellValue('C1', $formattedFacultyName);
    $sheet->setCellValue('B2', 'Program:');
    $sheet->setCellValue('C2', $students[0]['programcode']);  
    $sheet->setCellValue('B3', 'Grade and Section:');
    $sheet->setCellValue('C3', $formattedGradeLevel . ' - ' . $formattedSectionName); 
    $sheet->setCellValue('B4', 'Subject:');
    $sheet->setCellValue('C4', $students[0]['subjectname']); 
}else{
    $sheet->setCellValue('B1', 'Faculty:');
    $sheet->setCellValue('C1', $formattedFacultyName);
    $sheet->setCellValue('B2', 'Grade and Section:');
    $sheet->setCellValue('C2', $formattedGradeLevel . ' - ' . $formattedSectionName); 
    $sheet->setCellValue('B3', 'Subject:');
    $sheet->setCellValue('C3', $students[0]['subjectname']); 
}

// Set alignment for header rows (A1:B4)
$sheet->getStyle('B1:C4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('B1:C4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

// Bold only the variables (B1:B4)
$sheet->getStyle('C1:C4')->getFont()->setBold(true);

// Set the main header for columns in row 5
if($semID != 0){
    $sheet->setCellValue('A5', '#');
    $sheet->setCellValue('B5', 'LRN');
    $sheet->setCellValue('C5', 'Student Names');
    $sheet->setCellValue('D5', ($semID == 1) ? '1st Qtr' : '3rd Qtr');
    $sheet->setCellValue('E5', ($semID == 1) ? '2nd Qtr' : '4th Qtr');
}else{
    $sheet->setCellValue('A5', '#');
    $sheet->setCellValue('B5', 'LRN');
    $sheet->setCellValue('C5', 'Student Names');
    $sheet->setCellValue('D5', '1st Qtr');
    $sheet->setCellValue('E5', '2nd Qtr');
    $sheet->setCellValue('F5', '3rd Qtr');
    $sheet->setCellValue('G5', '4th Qtr');
}

// Set bold font for column headers and alignment
$sheet->getStyle('A5:G5')->getFont()->setBold(true);
$sheet->getStyle('A5:G5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


// Fill student data starting from row 6
$row = 6; // Start from row 6
$counter = 1; // Counter for the numbering column
foreach ($students as $student) {
    // Fill student data
    $studentName = strtolower($student['studName']); // Convert the name to lowercase
    $formattedStudentName = ucwords($studentName);

    if($semID != 0){
        // Write sequential number in column A, student name in column B, and grades in columns C, D, and E
        $sheet->setCellValue('A' . $row, $counter . '.'); // Add the number
        $sheet->setCellValue('B' . $row, "'" . $student['lrn']);  // Prepend single quote to LRN
        $sheet->setCellValue('C' . $row, $formattedStudentName); // Student name
        $sheet->setCellValue('D' . $row, $student['grade'] ?? '');  // 1st Qtr
        $sheet->setCellValue('E' . $row, $student['grade2'] ?? ''); // 2nd Qtr

        // Add hidden columns for secID, studID, facultyID, subjectID
        $sheet->setCellValue('F' . $row, $student['secID']); // Hidden secID
        $sheet->setCellValue('G' . $row, $student['studID']); // Hidden studID
        $sheet->setCellValue('H' . $row, $facultyID); // Hidden facultyID
        $sheet->setCellValue('I' . $row, $subjectID); // Hidden subjectID
        $sheet->setCellValue('J' . $row, $student['enrollID']); // Hidden subjectID
        $sheet->setCellValue('M' . $row, $student['ayName']); // Hidden subjectID
        $sheet->setCellValue('N' . $row, $student['gradelvlID']); // Hidden subjectID


        $sheet->setCellValue('R' . $row, $semID); // Hidden subjectID
        $sheet->setCellValue('S' . $row, $deptID); // Hidden deptID



        // Hide columns F, G, H, I (secID, studID, facultyID, subjectID)
        $sheet->getColumnDimension('F')->setVisible(false);
        $sheet->getColumnDimension('G')->setVisible(false);
        $sheet->getColumnDimension('H')->setVisible(false);
        $sheet->getColumnDimension('I')->setVisible(false);
        $sheet->getColumnDimension('J')->setVisible(false);
        $sheet->getColumnDimension('M')->setVisible(false);
        $sheet->getColumnDimension('N')->setVisible(false);

        $sheet->getColumnDimension('R')->setVisible(false);
        $sheet->getColumnDimension('S')->setVisible(false);



        // Set column widths for other visible columns (A-E)
        $sheet->getColumnDimension('A')->setWidth(5); // Column A for numbering
        $sheet->getColumnDimension('B')->setWidth(10); // Column B for numbering
        $sheet->getColumnDimension('C')->setWidth(60); // Column C for Student Names
        $sheet->getColumnDimension('D')->setWidth(10); // Column D for 1st Qtr
        $sheet->getColumnDimension('E')->setWidth(10); // Column E for 2nd Qtr

    }else{
        // Write sequential number in column A, student name in column B, and grades in columns C, D, and E
        $sheet->setCellValue('A' . $row, $counter . '.'); // Add the number
        $sheet->setCellValue('B' . $row, "'" . $student['lrn']);  // Prepend single quote to LRN
        $sheet->setCellValue('C' . $row, $formattedStudentName); // Student name
        $sheet->setCellValue('D' . $row, $student['grade'] ?? '');  // 1st Qtr
        $sheet->setCellValue('E' . $row, $student['grade2'] ?? ''); // 2nd Qtr
        $sheet->setCellValue('F' . $row, $student['grade3'] ?? '');  
        $sheet->setCellValue('G' . $row, $student['grade4'] ?? '');

        // Add hidden columns for secID, studID, facultyID, subjectID
        $sheet->setCellValue('H' . $row, $student['secID']); // Hidden secID
        $sheet->setCellValue('I' . $row, $student['studID']); // Hidden studID
        $sheet->setCellValue('J' . $row, $facultyID); // Hidden facultyID
        $sheet->setCellValue('K' . $row, $subjectID); // Hidden subjectID
        $sheet->setCellValue('L' . $row, $student['enrollID']); // Hidden subjectID
        $sheet->setCellValue('O' . $row, $student['ayName']); // Hidden subjectID
        $sheet->setCellValue('P' . $row, $student['gradelvlID']); // Hidden subjectID
        $sheet->setCellValue('Q' . $row, $activeSem); // Hidden subjectID


        $sheet->setCellValue('R' . $row, $semID); // Hidden subjectID
        $sheet->setCellValue('S' . $row, $deptID); // Hidden deptID



        // Hide columns F, G, H, I (secID, studID, facultyID, subjectID)
        $sheet->getColumnDimension('H')->setVisible(true);
        $sheet->getColumnDimension('I')->setVisible(true);
        $sheet->getColumnDimension('J')->setVisible(true);
        $sheet->getColumnDimension('K')->setVisible(true);
        $sheet->getColumnDimension('L')->setVisible(true);
        $sheet->getColumnDimension('O')->setVisible(true);
        $sheet->getColumnDimension('P')->setVisible(true);
        $sheet->getColumnDimension('Q')->setVisible(true);

        $sheet->getColumnDimension('R')->setVisible(true);
        $sheet->getColumnDimension('S')->setVisible(true);



        // Set column widths for other visible columns (A-E)
        $sheet->getColumnDimension('A')->setWidth(5); // Column A for numbering
        $sheet->getColumnDimension('B')->setWidth(10); // Column B for numbering
        $sheet->getColumnDimension('C')->setWidth(60); // Column C for Student Names
        $sheet->getColumnDimension('D')->setWidth(10); // Column D for 1st Qtr
        $sheet->getColumnDimension('E')->setWidth(10); // Column D for 1st Qtr
        $sheet->getColumnDimension('F')->setWidth(10); // Column D for 1st Qtr
        $sheet->getColumnDimension('G')->setWidth(10); // Column D for 1st Qtr
        $sheet->getColumnDimension('E')->setWidth(10); // Column E for 2nd Qtr
    }

    $counter++; // Increment the counter for the next row
    $row++; // Move to the next row
}


// Apply borders to all rows from row 5 onward
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];


    if ($semID != 0){
        $sheet->getStyle('A5:E' . ($row - 1))->applyFromArray($styleArray); // Apply borders to all cells from row 5 onwards

            // Apply bold borders specifically for row 5 (header row)
            $boldBorderStyle = [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                ],
            ];

            // Apply bold borders to row 5 (header)
            $sheet->getStyle('A5:E5')->applyFromArray($boldBorderStyle);
    }else{
        $sheet->getStyle('A5:G' . ($row - 1))->applyFromArray($styleArray); // Apply borders to all cells from row 5 onwards

        // Apply bold borders specifically for row 5 (header row)
        $boldBorderStyle = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
            ],
        ];

        // Apply bold borders to row 5 (header)
        $sheet->getStyle('A5:G5')->applyFromArray($boldBorderStyle);
            }

// Set file headers for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="gradesheet_temp.xlsx"');
header('Cache-Control: max-age=0');

// Write the file to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

?>