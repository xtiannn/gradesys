<?php
// Include PhpSpreadsheet library
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once "../includes/config.php"; // Include database connection

try {
    $sqlGP = "SELECT _first, _second FROM gradepermission";
    $stmtGP = $conn->prepare($sqlGP);
    $stmtGP->execute();

    $resultGP = $stmtGP->fetch(PDO::FETCH_ASSOC);

    $first = $resultGP['_first'];
    $second = $resultGP['_second'];
} catch (\Throwable $e) {
    echo "Error fetching: " . $e->getMessage();
}


if (isset($_POST['submit'])) {
    $programID = $_POST['programID'];
    $success = false;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // Check if the file is an Excel file
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (!in_array($extension, ['xls', 'xlsx'])) {
            echo "Invalid file type. Only Excel files (.xls, .xlsx) are allowed.";
            exit;
        }

        try {
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();

            // Iterate through the rows starting from row 6 (where the student data begins)
            $row = 6;
            while ($sheet->getCell('A' . $row)->getValue() != '') { // Check if row is not empty
                // Retrieve data from columns
                $lrn = $sheet->getCell('B' . $row)->getValue(); // LRN (column B)
                $studname = $sheet->getCell('C' . $row)->getValue();
                $semID = $sheet->getCell('R' . $row)->getValue(); 
                $deptID = $sheet->getCell('S' . $row)->getValue(); 

                if($deptID == 3){
                    // Retrieve values from hidden columns (F, G, H, I, J, K, L)
                    $secID = $sheet->getCell('F' . $row)->getValue(); 
                    $subjectID = $sheet->getCell('G' . $row)->getValue(); 
                    $facultyID = $sheet->getCell('H' . $row)->getValue();
                    $studID = $sheet->getCell('I' . $row)->getValue(); 
                    $enrollID = $sheet->getCell('J' . $row)->getValue(); 
                    $ayName = $sheet->getCell('M' . $row)->getValue(); 
                    $gradelvlID = $sheet->getCell('N' . $row)->getValue(); 




                    if($first == 1 && $second == 0){
                        $grade = $sheet->getCell('D' . $row)->getValue(); // 1st Quarter grade (column D)
                        $gradeCol = 'grade';
                    }elseif($first == 0 && $second == 1){
                        $grade = $sheet->getCell('E' . $row)->getValue(); 
                        $gradeCol = 'grade2';
                    }
                }else{
                    // Retrieve values from hidden columns (F, G, H, I, J, K, L)
                    $secID = $sheet->getCell('H' . $row)->getValue(); 
                    $subjectID = $sheet->getCell('I' . $row)->getValue(); 
                    $facultyID = $sheet->getCell('J' . $row)->getValue();
                    $studID = $sheet->getCell('K' . $row)->getValue(); 
                    $enrollID = $sheet->getCell('L' . $row)->getValue(); 

                    $ayName = $sheet->getCell('O' . $row)->getValue(); 
                    $gradelvlID = $sheet->getCell('P' . $row)->getValue(); 
                    $activeSem = $sheet->getCell('Q' . $row)->getValue(); 

                    if($activeSem == 1){
                        if($first == 1 && $second == 0){
                            $grade = $sheet->getCell('D' . $row)->getValue(); // 1st Quarter grade (column D)
                            $gradeCol = 'grade';
                        }elseif($first == 0 && $second == 1){
                            $grade = $sheet->getCell('E' . $row)->getValue(); 
                            $gradeCol = 'grade2';
                        }
                    }else{
                        if($first == 1 && $second == 0){
                            $grade = $sheet->getCell('F' . $row)->getValue(); 
                            $gradeCol = 'grade3';
                        }elseif($first === 0 && $second == 1){
                            $grade = $sheet->getCell('G' . $row)->getValue();
                            $gradeCol = 'grade4';
                        }
                    }
                }

                $valsecID = isset($_POST['valsecID']) ? $_POST['valsecID'] : null; //from hidden input in form
                $valsemID = isset($_POST['valsemID']) ? $_POST['valsemID'] : null; //from hidden input in form
                $valayName = isset($_POST['valsecAy']) ? $_POST['valsecAy'] : null; //from hidden input in form
                $valfacultyID = isset($_POST['valfacultyID']) ? $_POST['valfacultyID'] : null; //from hidden input in form
                $valprogramID = isset($_POST['valprogramID']) ? $_POST['valprogramID'] : null; //from hidden input in form
                $valstudID = isset($_POST['valstudID']) ? $_POST['valstudID'] : null; //from hidden input in form



                if (empty($lrn) || empty($studname)) {
                    header("Location: ../stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&programID=$programID&gradelvlID=$gradelvlID&deptID=$deptID&subjectID=0&excstatus=invalid");

                    exit;
                }
                if($deptID == 3){
                    if (($semID != $valsemID) || ($secID != $valsecID) || ($ayName != $valayName) || ($studID != $valstudID)) {
                        header("Location: ../stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&programID=$programID&gradelvlID=$gradelvlID&deptID=$deptID&subjectID=0&excstatus=notMatched");
                        exit();
                    }
                }else{
                    if (($secID != $valsecID) || ($ayName != $valayName) || ($studID != $valstudID)) {
                        header("Location: ../stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&programID=$programID&gradelvlID=$gradelvlID&deptID=$deptID&subjectID=0&excstatus=notMatched");
                        exit();

                    }
                }


                // Check if record already exists to avoid duplication
                $checkSql = "SELECT COUNT(*) FROM student_grades WHERE studID = :studID AND enrollID = :enrollID AND subjectID = :subjectID AND semID = :semID";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                $checkStmt->bindParam(':enrollID', $enrollID, PDO::PARAM_INT);
                $checkStmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $checkStmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                $checkStmt->execute();

                $exists = $checkStmt->fetchColumn();

                if ($exists > 0) {
                    // If record exists, update the existing one
                    $updateSql = "UPDATE student_grades SET 
                                    $gradeCol = :grade 
                                  WHERE studID = :studID AND enrollID = :enrollID AND subjectID = :subjectID AND semID = :semID";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bindParam(':grade', $grade, PDO::PARAM_STR);
                    $updateStmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                    $updateStmt->bindParam(':enrollID', $enrollID, PDO::PARAM_INT);
                    $updateStmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                    $updateStmt->bindParam(':semID', $semID, PDO::PARAM_INT);

                    // Execute the update query
                    $updateStmt->execute();
                } else {
                    // If record does not exist, insert new record
                    $insertSql = "INSERT INTO student_grades (studID, ayName, enrollID, subjectID, gradelvlID, semID, secID, $gradeCol) 
                                                        VALUES (:studID, :ayName, :enrollID, :subjectID, :gradelvlID, :semID, :secID, :grade)";
                    $insertStmt = $conn->prepare($insertSql);
                    $insertStmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                    $insertStmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);
                    $insertStmt->bindParam(':enrollID', $enrollID, PDO::PARAM_INT);
                    $insertStmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                    $insertStmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                    $insertStmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                    $insertStmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                    $insertStmt->bindParam(':grade', $grade, PDO::PARAM_STR);

                    // Execute the insert query
                    $insertStmt->execute();
                }

                $row++; // Move to the next row
            }
                $success = true;
        } catch (Exception $e) {
            $success = false;

            if($deptID == 3){
                header("Location: ../stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&semID=$semID&programID=$valprogramID&programID=$programID&gradelvlID=$gradelvlID&deptID=$deptID&subjectID=0&excstatus=failed&msg=" . $e->getMessage());        

            }else{
                header("Location: ../stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&programID=$programID&gradelvlID=$gradelvlID&deptID=$deptID&subjectID=0&excstatus=failed&msg=" . $e->getMessage());        
            }
        }
            if ($success) {
                if($deptID == 3){
                    header("Location: ../stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&semID=$semID&programID=$valprogramID&gradelvlID=$gradelvlID&deptID=$deptID&subjectID=0&excstatus=success");
                }else{
                    header("Location: ../stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&programID=$programID&gradelvlID=$gradelvlID&deptID=$deptID&subjectID=0&excstatus=success");
                }
            }
    } else {
        if($deptID == 3){
            header("Location: ../stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&semID=$semID&programID=$valprogramID&programID=$programID&gradelvlID=$gradelvlID&deptID=$deptID&subjectID=0&excstatus=noFile");
        }else{
            header("Location: ../stud_subject.php?studID=$studID&facultyID=$facultyID&secID=$secID&programID=$programID&gradelvlID=$gradelvlID&deptID=$deptID&subjectID=0&excstatus=noFile");
        }
    }
}
?>
