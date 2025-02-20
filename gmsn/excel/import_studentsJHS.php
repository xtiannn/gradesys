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

    // Grade level from C4:C5
    $gradeLevel = $sheet->getCell('C4')->getValue() . ' ' . $sheet->getCell('C5')->getValue();
    
    $stmt = $conn->prepare("SELECT gradelvlID FROM grade_level WHERE gradelvlcode = :gradelvlcode");
    $stmt->bindParam(':gradelvlcode', $gradeLevel, PDO::PARAM_STR);
    $stmt->execute();
    $gradeLevel = $stmt->fetch(PDO::FETCH_ASSOC);
    $gradelvlID = $gradeLevel['gradelvlID'] ?? null;

    $fullName = $sheet->getCell('Y1')->getValue();
    $stmt = $conn->prepare("SELECT facultyID FROM faculty WHERE fullName = :fullName");
    $stmt->bindParam(':fullName', $fullName, PDO::PARAM_STR);  
    $stmt->execute();
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);
    $facultyID = $faculty['facultyID'] ?? null;

    $excelAyName = $sheet->getCell('N4')->getValue();


    // Section name from D4:F5 (merged cells)
    $sectionName = $sheet->getCell('D4')->getValue() . ' ' . $sheet->getCell('E4')->getValue() . ' ' . $sheet->getCell('F4')->getValue();
    
    $stmt = $conn->prepare("SELECT secID, ayName FROM sections WHERE secName = :secName AND ayName = :ayName");
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
        $stmt = $conn->prepare("INSERT IGNORE INTO section_students (ayName, adviserID, secID, studID, gradelvlID) 
                                VALUES (:ayName, :adviserID, :secID, :studID, :gradelvlID)");
        foreach ($studentIDs as $id) {
            $stmt->bindParam(':ayName', $ayName, PDO::PARAM_STR);
            $stmt->bindParam(':adviserID', $facultyID, PDO::PARAM_INT);
            $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
            $stmt->bindParam(':studID', $id, PDO::PARAM_INT);
            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
            $stmt->execute();
        }
    
        echo "<br>Student IDs have been successfully inserted into section_students.<br>";
    } else {
        echo "<br>No valid student IDs found to insert.<br>";
    }
    

    
    
         
    if (isset($_SERVER['HTTP_REFERER'])) {
        $redirectUrl = $_SERVER['HTTP_REFERER'];
        
        // If there are unregistered students, append them to the redirect URL as a single query parameter
        if (!empty($unregisteredStudents)) {
            $unregistered = implode(',', $unregisteredStudents); // Concatenate unregistered students into a comma-separated string
            $redirectUrl .= (strpos($redirectUrl, '?') === false ? '?' : '&') . "unregistered=$unregistered";
        }
    
        // Append success=true, ensuring it's properly attached to the query string
        $redirectUrl .= (strpos($redirectUrl, '?') === false ? '?' : '&') . "success=true";
    
        // Redirect to the updated URL
        header("Location: $redirectUrl");
        exit;
    }
    
    
    



}
?>
