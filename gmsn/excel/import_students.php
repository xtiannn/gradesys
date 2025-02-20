<?php
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
require '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {

    $hiddenprogramID = $_POST['programID'] ?? NULL;
    $hiddengradelvlID = $_POST['gradelvlID'] ?? NULL;
    $hiddensecID = $_POST['secID'] ?? NULL;
    $hiddensemID = $_POST['semID'] ?? NULL;
    $hiddenfacultyID = $_POST['facultyID'] ?? NULL;
    $hiddenActiveAY = $_POST['activeAY'] ?? NULL;

    // Load the uploaded Excel file
    $file = $_FILES['file']['tmp_name'];
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK || $_FILES['file']['size'] === 0) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&noFile=true");
            exit();
        } else {
            exit("Error: No file uploaded or the file is empty.");
        }
    }

    $spreadsheet = IOFactory::load($file);

    // Access the first sheet (index 0)
    $sheet = $spreadsheet->getActiveSheet();


    $excelAyName = $sheet->getCell('N4')->getValue();



    // Program name from C1:F2 (merged cells)
    $program = $sheet->getCell('C1')->getValue() . ' ' . $sheet->getCell('D1')->getValue() . ' ' . $sheet->getCell('E1')->getValue() . ' ' . $sheet->getCell('F1')->getValue();
    
    $stmt = $conn->prepare("SELECT programID FROM programs WHERE programcode = :programcode");
    $stmt->bindParam(':programcode', $program, PDO::PARAM_STR);
    $stmt->execute();
    $program = $stmt->fetch(PDO::FETCH_ASSOC);
    $programID = $program['programID'] ?? null;

    // Grade level from C4:C5
    $gradeLevel = $sheet->getCell('C4')->getValue() . ' ' . $sheet->getCell('C5')->getValue();
    
    $stmt = $conn->prepare("SELECT gradelvlID FROM grade_level WHERE gradelvlcode = :gradelvlcode");
    $stmt->bindParam(':gradelvlcode', $gradeLevel, PDO::PARAM_STR);
    $stmt->execute();
    $gradeLevel = $stmt->fetch(PDO::FETCH_ASSOC);
    $gradelvlID = $gradeLevel['gradelvlID'] ?? null;



    $facultyNum = $sheet->getCell('Y1')->getValue();
    $stmt = $conn->prepare("SELECT facultyID FROM faculty WHERE fullName = :fullName");
    $stmt->bindParam(':fullName', $facultyNum, PDO::PARAM_STR);  
    $stmt->execute();
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);
    $facultyID = $faculty['facultyID'] ?? null;



    // Term from Y4:AB5 (merged cells)
    $term = $sheet->getCell('Y4')->getValue() . ' ' . $sheet->getCell('Z4')->getValue() . ' ' . $sheet->getCell('AA4')->getValue() . ' ' . $sheet->getCell('AB4')->getValue();
    
    $stmt = $conn->prepare("SELECT semID FROM semester WHERE semName = :semName");
    $stmt->bindParam(':semName', $term, PDO::PARAM_STR);
    $stmt->execute();
    $semester = $stmt->fetch(PDO::FETCH_ASSOC);
    $semID = $semester['semID'] ?? null;


    // Section name from D4:F5 (merged cells)
    $sectionName = $sheet->getCell('D4')->getValue() . ' ' . $sheet->getCell('E4')->getValue() . ' ' . $sheet->getCell('F4')->getValue();
    
    $stmt = $conn->prepare("SELECT secID, ayName FROM sections 
                                    WHERE secName = :secName
                                    AND ayName = :ayName");
    $stmt->bindParam(':secName', $sectionName, PDO::PARAM_STR);
    $stmt->bindParam(':ayName', $excelAyName, PDO::PARAM_STR);
    $stmt->execute();
    $section = $stmt->fetch(PDO::FETCH_ASSOC);
    $secID = $section['secID'] ?? null;
    $ayName = $section['ayName'] ?? null;


    
    if ($hiddenActiveAY != $excelAyName) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&ayNameNotMatched=true");
            exit();
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

   
    if ($hiddenfacultyID != $facultyID) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&facNotMatched=true");
            exit();
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
    if ($hiddenprogramID != $programID) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&progNotMatched=true");
            exit();
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
    
    if ($hiddengradelvlID != $gradelvlID) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&lvlNotMatched=true");
            exit();
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
    if ($hiddensecID != $secID) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&secNotMatched=true");
            exit();
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
    
    if ($hiddensemID != $semID) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&semNotMatched=true");
            exit();
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    


    $row = 14;
    $studentNames = [];
    $studentIDs = [];
    $unregisteredStudents = []; // Array to store unregistered students
    
    while ($sheet->getCell("C$row")->getValue() != "") {
        $lrn = $sheet->getCell("C$row")->getValue();
    
        // Prepare the query to fetch the student's ID and name
        $stmt = $conn->prepare("SELECT studID, lname, fname, mname FROM students WHERE lrn = :lrn");
        $stmt->bindParam(':lrn', $lrn, PDO::PARAM_STR);
        $stmt->execute();
        $studRecord = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // If the student is registered, process their information
        if ($studRecord) {
            $studID = $studRecord['studID'];
            $firstName = $studRecord['fname'];
            $lastName = $studRecord['lname'];
            $middleName = $studRecord['mname'];
    
            // Combine names to create a full name (if middle name exists, use it as initial)
            $fullName = $lastName . ", " . $firstName . " " . ($middleName ? $middleName[0] . "." : "");
            $studentNames[] = $fullName;
            $studentIDs[] = $studID;
        } else {
            // If the student is not registered, add to unregistered students
            $unregisteredStudents[] = $lrn;
        }
    
        $row++;
    }
    
    // Insert the retrieved studIDs into the section_students table
    if (!empty($studentIDs)) {
        $stmt = $conn->prepare("INSERT IGNORE INTO section_students (ayName, adviserID, secID, studID, programID, semID, gradelvlID) 
                                VALUES (:ayName, :adviserID, :secID, :studID, :programID, :semID, :gradelvlID)");
        foreach ($studentIDs as $id) {
            $stmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);
            $stmt->bindParam(':adviserID', $facultyID, PDO::PARAM_INT);
            $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
            $stmt->bindParam(':studID', $id, PDO::PARAM_INT);
            $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
            $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
            $stmt->execute();
        }
    
        echo "<br>Student IDs have been successfully inserted into section_students.<br>";
    } else {
        echo "<br>No valid student IDs found to insert.<br>";
    }
    

    
    echo "Active AY: $hiddenActiveAY -  - ";
    echo "excel AY: $excelAyName";
    
         
    if (isset($_SERVER['HTTP_REFERER'])) {
        $redirectUrl = $_SERVER['HTTP_REFERER'];
        
        if (!empty($unregisteredStudents)) {
            $unregistered = implode(',', $unregisteredStudents); 
            $redirectUrl .= (strpos($redirectUrl, '?') === false ? '?' : '&') . "unregistered=$unregistered";
        }
    
        $redirectUrl .= (strpos($redirectUrl, '?') === false ? '?' : '&') . "success=true";
    
        header("Location: $redirectUrl");
        exit;
    }
    
    
    



}
?>
