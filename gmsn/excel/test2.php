<?php
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
require '../includes/config.php';
require '../../faculty/fetch/permission.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $hiddenFacultyID = $_POST['facultyID'] ?? NULL;
    $hiddenSubjectID = $_POST['subjectID'] ?? NULL;
    $hiddenSecID = $_POST['secID'] ?? NULL;
    $hiddenSemID = $_POST['semID'] ?? NULL;
    $deptID = $_POST['deptID'] ?? NULL;
    $hiddenStudID = $_POST['studID'] ?? NULL;
    $hiddenActiveAY = $_POST['activeAY'] ?? NULL;
    
    if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
        try {
            $filePath = $_FILES['file']['tmp_name'];
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            $mergedCells = $sheet->getMergeCells();

            $facultyName = '';
            $gradingPeriod = '';
            $secName = '';
            $gradelvlcode = '';
            $semName = '';
            $lrn = '';
            $studentname = '';
            $subjects = [];
            $enrolledSubjects = [];
            $notEnrolledSubjects = [];

            foreach ($mergedCells as $cellRange) {
                if ($cellRange == 'Y1:AB2') {
                    $facultyName = $sheet->getCell('Y1')->getValue();
                }
                if ($cellRange == 'C1:F2') {
                    $studentName = $sheet->getCell('C1')->getValue();
                }
                if ($cellRange == 'C3:F4') {
                    $lrn = $sheet->getCell('C3')->getValue();
                }
                if ($cellRange == 'Y4:AB5') {
                    $gradingPeriod = $sheet->getCell('W4')->getValue();
                }
                if ($cellRange == 'C5:C6') {
                    $gradelvlcode = $sheet->getCell('C5')->getValue();
                }
                if ($cellRange == 'D5:F6') {
                    $secName = $sheet->getCell('D5')->getValue();
                }
                if ($cellRange == 'Y4:AB5') {
                    $semName = $sheet->getCell('Y4')->getValue();
                }
                if ($cellRange == 'M4:Q5') {
                    $acadYear = $sheet->getCell('M4')->getValue();
                }
            }

            $stmt = $conn->prepare("SELECT facultyID FROM faculty WHERE fullName = :fullName");
            $stmt->bindParam(':fullName', $facultyName, PDO::PARAM_STR);
            $stmt->execute();
            $faculty = $stmt->fetch(PDO::FETCH_ASSOC);
            $facultyID = $faculty['facultyID'] ?? null;

            $stmt = $conn->prepare("SELECT studID FROM students WHERE lrn = :lrn");
            $stmt->bindParam(':lrn', $lrn, PDO::PARAM_STR);
            $stmt->execute();
            $subject = $stmt->fetch(PDO::FETCH_ASSOC);
            $studID = $subject['studID'] ?? null;

            $excelAyName = $sheet->getCell('N4')->getValue();

            $stmt = $conn->prepare("SELECT secID, ayName FROM sections WHERE secName = :secName AND ayName = :ayName");
            $stmt->bindParam(':secName', $secName, PDO::PARAM_STR);
            $stmt->bindParam(':ayName', $excelAyName, PDO::PARAM_STR);
            $stmt->execute();
            $section = $stmt->fetch(PDO::FETCH_ASSOC);
            $secID = $section['secID'] ?? null;
            $ayName = $section['ayName'] ?? null;

            $stmt = $conn->prepare("SELECT gradelvlID FROM grade_level WHERE gradelvlcode = :gradelvlcode");
            $stmt->bindParam(':gradelvlcode', $gradelvlcode, PDO::PARAM_STR);
            $stmt->execute();
            $gradeLevel = $stmt->fetch(PDO::FETCH_ASSOC);
            $gradelvlID = $gradeLevel['gradelvlID'] ?? null;

            $stmt = $conn->prepare("SELECT semID FROM semester WHERE semName = :semName");
            $stmt->bindParam(':semName', $semName, PDO::PARAM_STR);
            $stmt->execute();
            $semester = $stmt->fetch(PDO::FETCH_ASSOC);
            $semID = $semester['semID'] ?? null;




            if ($hiddenActiveAY != $excelAyName) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "&ayNameNotMatched=true");
                    exit();
                } else {
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }

            if ($hiddenStudID != $studID) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "&studNotMatched=true");
                    exit();
                } else {
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            if ($hiddenFacultyID != $facultyID) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "&facNotMatched=true");
                    exit();
                } else {
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            if ($hiddenSecID != $secID) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "&secNotMatched=true");
                    exit();
                } else {
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            
            if ($hiddenSemID != $semID) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "&semNotMatched=true");
                    exit();
                } else {
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }

            


            $row = 14;
            while ($sheet->getCell("C$row")->getValue() != "") {
                $subjectCode = $sheet->getCell("C$row")->getValue();
                $firstGrade = $sheet->getCell("Q$row")->getValue();
                $secondGrade = $sheet->getCell("T$row")->getValue();


                $stmt = $conn->prepare("SELECT subjectID FROM subjects WHERE subjectcode = :subjectcode");
                $stmt->bindParam(':subjectcode', $subjectCode, PDO::PARAM_STR);
                $stmt->execute();
                $subjectRecord = $stmt->fetch(PDO::FETCH_ASSOC);

                $subjectID = $subjectRecord['subjectID'] ?? null;

                echo "Subject ID for $subjectCode: " . $subjectID . ' - '.$firstGrade. "<br>"; // Debugging line

                if ($subjectID) {
                    // Check enrollment in section_students
                    $stmt = $conn->prepare("
                        SELECT * FROM section_students 
                        WHERE subjectID = :subjectID 
                          AND secID = :secID 
                          AND gradelvlID = :gradelvlID 
                          AND semID = :semID 
                          AND ayName = :ayName
                    ");
                    $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                    $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                    $stmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);
                    $stmt->execute();

                    
                    if ($stmt->rowCount() > 0) {
                        $enrolledSubjects[] = $subjectCode;
                    
                        // Prepare first update query if applicable
                        if($firstSwitchValue == 0){
                            $stmt = $conn->prepare("UPDATE student_grades 
                                                    SET grade2 = :secondGrade 
                                                    WHERE subjectID = :subjectID
                                                    AND studID = :studID 
                                                    AND secID = :secID 
                                                    AND gradelvlID = :gradelvlID 
                                                    AND semID = :semID 
                                                    AND ayName = :ayName");
                            $stmt->bindParam(':secondGrade', $secondGrade, PDO::PARAM_STR);
                            $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                            $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                            $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                            $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                            $stmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);
                            $stmt->execute();
                        }
                    
                        // Prepare second update query if applicable
                        if($secondSwitchValue == 0){
                            $stmt = $conn->prepare("UPDATE student_grades 
                                                    SET grade = :firstGrade
                                                    WHERE subjectID = :subjectID 
                                                    AND studID = :studID
                                                    AND secID = :secID 
                                                    AND gradelvlID = :gradelvlID 
                                                    AND semID = :semID 
                                                    AND ayName = :ayName");
                            $stmt->bindParam(':firstGrade', $firstGrade, PDO::PARAM_STR);
                            $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                            $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                            $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                            $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                            $stmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);
                            $stmt->execute();
                        }
                    
                        // Update fgrade for semID NOT NULL
                        $query_update_fgrade = "
                            UPDATE student_grades
                            SET fgrade = (COALESCE(grade, 0) + COALESCE(grade2, 0)) / 2,
                                remarks = CASE
                                            WHEN (COALESCE(grade, 0) + COALESCE(grade2, 0)) / 2 < 75 THEN 0  -- Failed
                                            WHEN (COALESCE(grade, 0) + COALESCE(grade2, 0)) / 2 >= 75 THEN 1  -- Passed
                                          END
                            WHERE semID IS NOT NULL 
                            AND grade IS NOT NULL 
                            AND grade2 IS NOT NULL;
                        ";
                        $stmtFgrade = $conn->prepare($query_update_fgrade);
                        $stmtFgrade->execute();
                    
                    } else {
                        $notEnrolledSubjects[] = $subjectCode;
                    }
                    
                } else {
                    $notEnrolledSubjects[] = $subjectCode;
                }

                $row++;
            }


            


     
            if (isset($_SERVER['HTTP_REFERER'])) {
                $queryParams = [
                    'notEnrolled' => $notEnrolledStudents
                ];
            
                $redirectUrl = $_SERVER['HTTP_REFERER'];
            
                header("Location: $redirectUrl");
                exit;
            }
            
        

            foreach ($enrolledSubjects as $subjectCode) {
                // Fetch student details
                $stmt = $conn->prepare("SELECT * FROM subjects WHERE subjectcode = :subjectcode");
                $stmt->bindParam(':subjectcode', $subjectCode, PDO::PARAM_STR);
                $stmt->execute();
                $subject = $stmt->fetch(PDO::FETCH_ASSOC);

                // Assign grade based on semID
                if ($semID == 1) {
                    $grade = ($firstSwitchValue == 1) ? $firstGrade : (($secondSwitchValue == 1) ? $secondGrade : '');
                } elseif ($semID == 2) {
                    $grade = ($firstSwitchValue == 1) ? $firstGrade : (($secondSwitchValue == 1) ? $secondGrade : '');
                } else {
                    $grade = ''; // Default to empty if conditions are not met
                }

                echo "LRN: $subjectCode, Name: {$subject['subjectname']}, Grade: $grade<br>";
            }

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            echo 'Error loading file: ' . $e->getMessage();
        }
    } else {
        echo "File upload error.";
    }
}
?>
