<?php
include 'session.php';

require_once "includes/config.php";
require_once "fetch/fetch_activeAY.php";





if (isset($_GET['deptID'])) {
    $semID = $_GET['semID'] ?? '';
    $programID = $_GET['programID'] ?? '';
    $secID = $_GET['secID'] ?? '';
    $gradelvlID = $_GET['gradelvlID'] ?? '';
    $ayID = $_GET['ayID'] ?? '';
    $deptID = $_GET['deptID'] ?? '';
    $facultyID = $_GET['facultyID'] ?? '';


}

//fetching the ayName of a section
try {
    $sectionAyName = "SELECT s.ayName, s.secName, p.programname, gl.gradelvl, semID,
                        CONCAT(f.lname, ', ', f.fname, ' ', f.mname) AS facultyname,
                        f.gender
                        FROM sections s 
                        LEFT JOIN programs p ON s.programID = p.programID
                        JOIN faculty f ON s.facultyID = f.facultyID
                        JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
                        WHERE secID = :secID";    
    $stmtAyName = $conn->prepare($sectionAyName);
    $stmtAyName->bindParam('secID', $secID, PDO::PARAM_INT);
    $stmtAyName->execute();

    $resultAyName = $stmtAyName->fetch(PDO::FETCH_ASSOC);
    $secAyName = $resultAyName['ayName'];
    $secSem = $resultAyName['semID'] ?? NULL;
    $sectionName = ucwords(strtolower(trim($resultAyName['secName'])));
    $programCode = strtoupper($resultAyName['programname']) ?? NULL;
    $gradelvlCode = $resultAyName['gradelvl'];
    $gender = $resultAyName['gender'];
    
    if($gender == 'Male'){
        $title = 'Mr. ';
    }else{
        $title = 'Ms. ';
    }
    $facultyname = ucwords(strtolower($resultAyName['facultyname']));


} catch (\Throwable $e) {
    echo "Error fetching academic year: " . $e->getMessage();

}



?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Enrolled Students</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/gmsnlogo.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>
<style>
    td{
        font-size: 14px;
    }
    .bg-warning {
        background-color: #fff3cd;
    }
    .badge.bg-warning {
        font-size: .7rem;
        color: #856404;
        background-color: #fff3cd;
    }
    td{
        font-size: 14px;
    }
    th{
        font-size: 15px;
    }
    .custom-container {
      margin-left: -10px;
      margin-right: -15px;
    }
    .custom-container {
      width: 100%; 
    }
    .custom-card-title {
    padding: 1px 0;
    margin: 2px 2px;
    font-size: 20px;
    font-weight: 600;
    color: #012970;
    font-family: "Poppins", sans-serif;
    }

    .custom-card-title span {
    color: #012970;
    font-size: 15px;
    font-weight: 400;
    margin-left: 8px;
    }


</style>
<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>

    <main id="main" class="main">
        <div id="loader" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1050; display: flex; justify-content: center; align-items: center;">
            <div class="text-center">
                <div class="spinner-border text-light" style="width: 4rem; height: 4rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <!-- <p class="text-light mt-3">Processing your request...</p> -->
            </div>
        </div>
        <section class="section">
            <div class="custom-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="section_builder.php?deptID=<?php echo $deptID;?>">Sections</a></li>
                      <li class="breadcrumb-item active">Manage Students</li>
                    </ol>
                </nav>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-start">
                                <div class="mt-0">
                                    <?php if($deptID == 3):?>
                                        <div class="d-flex">
                                            <h6 class="custom-card-title fw-normal" style="font-size: 14px; margin: 0; width: 80px;">Program:</h6>
                                            <span class="custom-card-title" style="font-weight: 700; font-size: 14px; margin: 0;"><?php echo ucwords(strtolower( $programCode)); ?></span>
                                        </div>
                                    <?php else:?>
                                        <div class="d-flex">
                                            <h6 class="custom-card-title fw-normal" style="font-size: 14px; margin: 0; width: 80px;">Adviser:</h6>
                                            <span class="custom-card-title" style="font-weight: 700; font-size: 14px; margin: 0;"><?php echo $title.$facultyname; ?></span>
                                        </div>
                                    <?php endif;?>
                                    <div class="d-flex">
                                        <h6 class="custom-card-title fw-normal" style="font-size: 14px; margin: 0; width: 80px;">Level:</h6>
                                        <span class="custom-card-title" style="font-weight: 700; font-size: 14px; margin: 0;"><?php echo $gradelvlCode; ?></span>
                                    </div>
                                    <div class="d-flex">
                                        <h6 class="custom-card-title fw-normal" style="font-size: 14px; margin: 0; width: 80px;">Section:</h6>
                                        <span class="custom-card-title" style="font-weight: 700; font-size: 14px; margin: 0;"><?php echo $sectionName; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-0">
                                <?php if($deptID == 3):?>
                                    <button 
                                        style="<?php echo ($secAyName != $activeAY) ? 'display: none;' : ''?>"
                                        class="btn btn-primary btn-sm enrollSHS-stud-btn me-2" 
                                        data-program-id="<?php echo $programID?>"
                                        data-gradelvl-id="<?php echo $gradelvlID?>"
                                        data-sec-id="<?php echo $secID?>"
                                        data-sec-name="<?php echo $sectionName?>"
                                        data-dept-id="<?php echo $deptID?>"
                                        data-faculty-id="<?php echo $facultyID?>"
                                    >
                                        <i class="bi bi-person-add"></i> Enroll Student
                                    </button>
                                <?php else:?>
                                    <button 
                                        style="<?php echo ($secAyName != $activeAY) ? 'display: none;' : ''?>"
                                        class="btn btn-primary btn-sm enroll-stud-btn me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#addStudentSec"
                                        data-program-id="<?php echo $programID?>"
                                        data-gradelvl-id="<?php echo $gradelvlID?>"
                                        data-sec-id="<?php echo $secID?>"
                                        data-sec-name="<?php echo $sectionName?>"
                                        data-dept-id="<?php echo $deptID?>"
                                        data-faculty-id="<?php echo $facultyID?>"
                                        >
                                        <i class="bi bi-person-add"></i> Enroll Student
                                    </button> 
                                <?php endif;?>
                                <form action="excel/<?php echo ($deptID == 3) ? 'import_students.php' : 'import_studentsJHS.php'?>" method="POST" enctype="multipart/form-data" class="mt-4">
                                    <div class="input-group mt-1">
                                        <!-- Hidden file input -->
                                        <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control form-control-sm mt-3" style="display: none;" />
                                        
                                        <!-- Label triggers file input -->
                                        <label for="file" class="btn btn-outline-success btn-sm">
                                            Import Students
                                        </label>
                                        
                                        <!-- Submit button -->
                                        <button type="submit" name="submit" style="height: 31px" class="btn btn-success btn-sm">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                    </div>
                                    <!-- File name preview -->
                                    <div id="fileNamePreview" style="font-size: 12px; color: #555; visibility: hidden; height: 20px; line-height: 20px;">
                                    </div>
                                        <input type="hidden" name="programID" value="<?php echo $programID?>">
                                        <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID?>">
                                        <input type="hidden" name="secID" value="<?php echo $secID?>">
                                        <input type="hidden" name="semID" value="<?php echo $semID?>">
                                        <input type="hidden" name="facultyID" value="<?php echo $facultyID?>">
                                        <input type="hidden" name="activeAY" value="<?php echo $activeAY?>">
                                </form> 
                                <script>
                                    // JavaScript to show file name after file is selected
                                    document.getElementById('file').addEventListener('change', function() {
                                        var fileInput = document.getElementById('file');
                                        var fileNamePreview = document.getElementById('fileNamePreview');
                                        
                                        // Check if a file is selected
                                        if (fileInput.files.length > 0) {
                                            var fileName = fileInput.files[0].name; 
                                            fileNamePreview.textContent = fileName;
                                            fileNamePreview.style.visibility = 'visible';
                                        } else {
                                            fileNamePreview.style.visibility = 'hidden';
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($deptID == 3):?>
                                <?php 
                                $curriculum = [];
                                if (isset($_GET['programID'], $_GET['gradelvlID'], $_GET['semID'])) {
                                    $programID = $_GET['programID'];
                                    $gradelvlID = $_GET['gradelvlID'];
                                    $semID = $_GET['semID'];
                                    $secID = $_GET['secID'];
                                    $facultyID = $_GET['facultyID'];

                                    require_once("includes/config.php");

                                    $query = "SELECT ss.*, s.lrn, s.lname, s.fname, s.mname, s.address, s.contact, s.gender, sm.semCode
                                            FROM section_students ss
                                            JOIN students s ON ss.studID = s.studID
                                            JOIN semester sm ON ss.semID = sm.semID
                                            WHERE ss.semID = :semID
                                            AND ss.gradelvlID = :gradelvlID
                                            AND secID = :secID
                                            ORDER BY s.lname ASC";

                                    $stmt = $conn->prepare($query);
                                    $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $count = 0;
                                } else {
                                    echo "Not all required parameters are set!";
                                }

                                $processedStudIDs = []; // Array to track processed studIDs
                                ?>

                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 100px">#</th>
                                            <th class="text-center" style="width: 100px">A.Y.</th>
                                            <th class="text-center" style="width: 100px">Term</th>
                                            <th class="text-center" style="width: 100px">LRN</th>
                                            <th style="width: 100%">Name</th>
                                            <?php 
                                            $hasIncomplete = false;
                                            foreach ($curriculum as $row) {
                                                $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                                if ($isIncomplete) {
                                                    $hasIncomplete = true;
                                                    break;
                                                }
                                            }
                                            if ($hasIncomplete) {
                                                echo '<th class="text-center" style="width: 150px">Status</th>';
                                            }
                                            ?>
                                            <th class="text-center" style="width: 10px">Graded</th>
                                            <th class="text-center" style="width: 150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($curriculum as $row): 
                                            $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);

                                            // Skip duplicate studID
                                            if (in_array($row['studID'], $processedStudIDs)) {
                                                continue; // Skip this row if studID already processed
                                            }

                                            // Add studID to processed list
                                            $processedStudIDs[] = $row['studID'];
                                        ?>
                                            <tr class="<?php echo $isIncomplete ? 'bg-warning' : ''; ?>">
                                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                                <td class="text-center"><?php echo $row['ayName']; ?></td>
                                                <td class="text-center"><?php echo $row['semCode']; ?></td>
                                                <td class="text-center"><?php echo $row['lrn']; ?></td>
                                                <td>
                                                    <?php echo ucwords(strtolower($row['lname'])) . ', ' . ucwords(strtolower($row['fname'])) . ' ' . ucwords(strtolower($row['mname'])); ?>
                                                </td>
                                            <?php if ($hasIncomplete) { ?>
                                                <td class="text-center">
                                                    <?php if ($isIncomplete) { ?>
                                                        <a href="sh_studRecord_update.php?studID=<?php echo $row['studID']?>">
                                                            <span class="badge bg-warning">Inc Info</span>
                                                        </a>
                                                    <?php } else { ?>
                                                        <span></span>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                                <?php 
                                                    $rowStudID = $row['studID']; 
                                                    $query = "
                                                        SELECT 
                                                            COUNT(subjectID) AS total_subjects, 
                                                            COUNT(CASE WHEN fgrade IS NOT NULL THEN 1 END) AS graded_subjects
                                                        FROM student_grades 
                                                        WHERE studID = :studID
                                                        AND semID = :semID
                                                        AND secID = :secID
                                                        AND gradelvlID = :gradelvlID
                                                    ";

                                                    $stmt = $conn->prepare($query);

                                                    // Bind the parameters
                                                    $stmt->bindParam(':studID', $rowStudID, PDO::PARAM_INT);
                                                    $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);

                                                    $stmt->execute();

                                                    // Fetch the results
                                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    $totalSubjects = $result['total_subjects'];
                                                    $gradedSubjects = $result['graded_subjects'];

                                                    $gradedClass = ($totalSubjects == $gradedSubjects) ? ' text-success fw-bold' : '';
                                                ?>
                                                    <td class="text-center<?php echo $gradedClass?>">
                                                        <?php 
                                                        echo ($gradedSubjects == 0 && $totalSubjects == 0) ? "" : "$gradedSubjects/$totalSubjects"; 
                                                        ?>
                                                    </td>                                                        
                                                <td class="text-center">
                                                    <?php
                                                    // Build the URL
                                                   
                                                    $base_url = 'students_subj.php';
                                                    $params = [
                                                        'studID' => urlencode(trim($row['studID'])),
                                                        'semID' => urlencode(trim($row['semID'])),
                                                        'secID' => urlencode(trim($row['secID'])),
                                                        'gradelvlID' => urlencode(trim($row['gradelvlID'])),
                                                        'programID' => urlencode(trim($row['programID'])),
                                                        'subjectID' => urlencode(0),  // Hardcoded subjectID as 0
                                                        'ayID' => urlencode(trim($row['ayID'])),
                                                        'facultyID' => urlencode(trim($facultyID)),
                                                        'deptID' => urlencode(trim($deptID))
                                                    ];

                                                    // Construct the final URL
                                                    $query_string = http_build_query($params);
                                                    $final_url = $base_url . '?' . $query_string;
                                                    ?>
                                                    <a class="btn btn-primary btn-sm" 
                                                        style="font-size: 13px; height: 31px;"
                                                        data-bs-toggle="tooltip"
                                                        title="Enroll Subjects"
                                                        type="button" 
                                                        href="<?php echo $final_url; ?>">
                                                        <i class="bi bi-book me-1"></i> Subjects
                                                    </a>
                                                    <button 
                                                        style="<?php echo ($secAyName != $activeAY) ? 'display: none;' : ''?>"
                                                        class="btn btn-danger delete-btn btn-sm" 
                                                        data-bs-toggle="tooltip"
                                                        title="Unenroll Student"
                                                        data-enroll-id="<?php echo $row['enrollID']; ?>" 
                                                        data-stud-id="<?php echo $row['studID']; ?>">
                                                        <i class="bi bi-person-dash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            <?php elseif($deptID ==2):?>
                                <?php 
                                $curriculum = [];
                                if(isset($_GET['gradelvlID'])) {
                                    $gradelvlID = $_GET['gradelvlID'];
                                    $secID = $_GET['secID'];
                                    $facultyID = $_GET['facultyID'];

                                    require_once("includes/config.php");

                                    $query = "SELECT ss.*, 
                                        (SELECT lrn FROM students s WHERE ss.studID = s.studID) as lrn,
                                        (SELECT lname FROM students s WHERE ss.studID = s.studID) as lname,
                                        (SELECT fname FROM students s WHERE ss.studID = s.studID) as fname,
                                        (SELECT mname FROM students s WHERE ss.studID = s.studID) as mname,
                                        (SELECT address FROM students s WHERE ss.studID = s.studID) as address,
                                        (SELECT contact FROM students s WHERE ss.studID = s.studID) as contact,
                                        (SELECT gender FROM students s WHERE ss.studID = s.studID) as gender
                                        FROM section_students ss 
                                        WHERE ss.gradelvlID = :gradelvlID 
                                        AND secID = :secID";

                                    $stmt = $conn->prepare($query);
                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                    $count = 0;
                                    } 
                                    else {
                                        echo "Not all required parameters are set!";
                                    }

                                    $processedStudIDs = []; // Array to track processed studIDs

                                ?>
                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 100px">#</th>
                                            <th class="text-center" style="width: 100px">A.Y.</th>
                                            <th class="text-center" style="width: 100px">LRN</th>
                                            <th style="width: 100%">Name</th>
                                            <?php 
                                                $hasIncomplete = false;
                                                foreach ($curriculum as $row) {
                                                    $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                                    if ($isIncomplete) {
                                                        $hasIncomplete = true;
                                                        break;
                                                    }
                                                }
                                                if ($hasIncomplete) {
                                                    echo '<th class="text-center" style="width: 150px">Status</th>';
                                                }
                                            ?>
                                            <th class="text-center" style="width: 10px">Graded</th>
                                            <th class="text-center" style="width: 150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($curriculum as $row): 
                                            $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                            // Skip duplicate studID
                                            if (in_array($row['studID'], $processedStudIDs)) {
                                                continue; // Skip this row if studID already processed
                                            }

                                            // Add studID to processed list
                                            $processedStudIDs[] = $row['studID'];
                                        ?>
                                            <tr class="<?php echo $isIncomplete ? 'bg-warning' : ''; ?>">
                                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                                <td class="text-center"><?php echo $row['ayName']; ?></td>
                                                <td class="text-center"><?php echo $row['lrn']; ?></td>
                                                <td>
                                                    <?php echo ucwords(strtolower($row['lname'])) . ', ' . ucwords(strtolower($row['fname'])) . ' ' . ucwords(strtolower($row['mname'])); ?>
                                                </td>
                                            <?php if ($hasIncomplete) { ?>
                                                <td class="text-center">
                                                    <?php if ($isIncomplete) { ?>
                                                        <a href="sh_studRecord_update.php?studID=<?php echo $row['studID']?>">
                                                            <span class="badge bg-warning">Inc Info</span>
                                                        </a>
                                                    <?php } else { ?>
                                                        <span></span>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                                <?php 
                                                    $rowStudID = $row['studID']; 
                                                    $query = "
                                                        SELECT 
                                                            COUNT(subjectID) AS total_subjects, 
                                                            COUNT(CASE WHEN fgrade IS NOT NULL THEN 1 END) AS graded_subjects
                                                        FROM student_grades 
                                                        WHERE studID = :studID
                                                        AND secID = :secID
                                                        AND gradelvlID = :gradelvlID
                                                    ";

                                                    $stmt = $conn->prepare($query);

                                                    // Bind the parameters
                                                    $stmt->bindParam(':studID', $rowStudID, PDO::PARAM_INT);
                                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);

                                                    $stmt->execute();

                                                    // Fetch the results
                                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    $totalSubjects = $result['total_subjects'];
                                                    $gradedSubjects = $result['graded_subjects'];

                                                    $gradedClass = ($totalSubjects == $gradedSubjects) ? ' text-success fw-bold' : '';
                                                ?>
                                                    <td class="text-center<?php echo $gradedClass?>">
                                                        <?php 
                                                        echo ($gradedSubjects == 0 && $totalSubjects == 0) ? "" : "$gradedSubjects/$totalSubjects"; 
                                                        ?>
                                                    </td>    
                                                <td class="text-center">
                                                <?php
                                                    $studID = urlencode(trim($row['studID']));
                                                    $semID = urlencode(trim($row['semID']));
                                                    $secID = urlencode(trim($row['secID']));
                                                    $gradelvlID = urlencode(trim($row['gradelvlID']));
                                                    $programID = urlencode(trim($row['programID']));
                                                    $studName = urlencode(trim($row['lname']) . ', ' . trim($row['fname']) . ' ' . trim($row['mname']));
                                                    $subjectID = urlencode(0); // Hardcoded as 0
                                                    $ayID = urlencode(trim($row['ayID']));
                                                    $facultyID = urlencode(trim($facultyID));
                                                    $deptID = urlencode(trim($deptID));

                                                    $url = "students_subj.php?studID=$studID&semID=$semID&secID=$secID&gradelvlID=$gradelvlID&programID=$programID&subjectID=$subjectID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID";
                                                ?>

                                                    <a class="btn btn-primary btn-sm" 
                                                    style="font-size: 13px; height: 31px"
                                                        data-bs-toggle="tooltip"
                                                        title="Enroll Subjects"
                                                        type="button" href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="bi bi-book me-1"></i> Subjects
                                                    </a>

                                                    <?php if($secAyName != $activeAY):?>
                                                    <?php else:?>
                                                        <button class="btn btn-danger delete-btn btn-sm" 
                                                        data-bs-toggle="tooltip"
                                                        title="Unenroll Student"
                                                        data-enroll-id="<?php echo $row['enrollID']; ?>" 
                                                        data-stud-id="<?php echo $row['studID']; ?>">
                                                        <i class="bi bi-person-dash-fill"></i>
                                                    </button>
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else:?>
                                <?php 
                                $curriculum = [];
                                if(isset($_GET['gradelvlID'])) {
                                    $gradelvlID = $_GET['gradelvlID'];
                                    $secID = $_GET['secID'];
                                    $facultyID = $_GET['facultyID'];

                                    require_once("includes/config.php");

                                    $query = "SELECT ss.*, 
                                        (SELECT lrn FROM students s WHERE ss.studID = s.studID) as lrn,
                                        (SELECT lname FROM students s WHERE ss.studID = s.studID) as lname,
                                        (SELECT fname FROM students s WHERE ss.studID = s.studID) as fname,
                                        (SELECT mname FROM students s WHERE ss.studID = s.studID) as mname,
                                        (SELECT address FROM students s WHERE ss.studID = s.studID) as address,
                                        (SELECT contact FROM students s WHERE ss.studID = s.studID) as contact,
                                        (SELECT gender FROM students s WHERE ss.studID = s.studID) as gender
                                        FROM section_students ss 
                                        WHERE ss.gradelvlID = :gradelvlID 
                                        AND secID = :secID ORDER BY lname ASC, fname ASC, mname ASC";

                                    $stmt = $conn->prepare($query);
                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                    $count = 0;
                                    } 
                                    else {
                                        echo "Not all required parameters are set!";
                                    }

                                    $processedStudIDs = []; // Array to track processed studIDs

                                ?>
                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 100px">#</th>
                                            <th class="text-center" style="width: 100px">LRN</th>
                                            <th style="width: 100%">Name</th>
                                            <?php 
                                                $hasIncomplete = false;
                                                foreach ($curriculum as $row) {
                                                    $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                                    if ($isIncomplete) {
                                                        $hasIncomplete = true;
                                                        break;
                                                    }
                                                }
                                                if ($hasIncomplete) {
                                                    echo '<th class="text-center" style="width: 150px">Status</th>';
                                                }
                                            ?>
                                            <th class="text-center" style="width: 10px">Graded</th>
                                            <th class="text-center" style="width: 150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($curriculum as $row): 
                                            $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                            // Skip duplicate studID
                                            if (in_array($row['studID'], $processedStudIDs)) {
                                                continue; // Skip this row if studID already processed
                                            }

                                            // Add studID to processed list
                                            $processedStudIDs[] = $row['studID'];
                                        ?>
                                            <tr class="<?php echo $isIncomplete ? 'bg-warning' : ''; ?>">
                                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                                <td class="text-center"><?php echo $row['lrn']; ?></td>
                                                <td>
                                                    <?php echo ucwords(strtolower($row['lname'])) . ', ' . ucwords(strtolower($row['fname'])) . ' ' . ucwords(strtolower($row['mname'])); ?>
                                                </td>
                                            <?php if ($hasIncomplete) { ?>
                                                <td class="text-center">
                                                    <?php if ($isIncomplete) { ?>
                                                        <a href="sh_studRecord_update.php?studID=<?php echo $row['studID']?>">
                                                            <span class="badge bg-warning">Inc Info</span>
                                                        </a>
                                                    <?php } else { ?>
                                                        <span></span>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                                <?php 
                                                    $rowStudID = $row['studID']; 
                                                    $query = "
                                                        SELECT 
                                                            COUNT(subjectID) AS total_subjects, 
                                                            COUNT(CASE WHEN fgrade IS NOT NULL THEN 1 END) AS graded_subjects
                                                        FROM student_grades 
                                                        WHERE studID = :studID
                                                        AND secID = :secID
                                                        AND gradelvlID = :gradelvlID
                                                    ";

                                                    $stmt = $conn->prepare($query);

                                                    // Bind the parameters
                                                    $stmt->bindParam(':studID', $rowStudID, PDO::PARAM_INT);
                                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);

                                                    $stmt->execute();

                                                    // Fetch the results
                                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    $totalSubjects = $result['total_subjects'];
                                                    $gradedSubjects = $result['graded_subjects'];

                                                    $gradedClass = ($totalSubjects == $gradedSubjects) ? ' text-success fw-bold' : '';
                                                ?>
                                                    <td class="text-center<?php echo $gradedClass?>">
                                                        <?php 
                                                        echo ($gradedSubjects == 0 && $totalSubjects == 0) ? "" : "$gradedSubjects/$totalSubjects"; 
                                                        ?>
                                                    </td>
  
                                                <td class="text-center">
                                                <?php
                                                    $studID = urlencode(trim($row['studID']));
                                                    $semID = urlencode(trim($row['semID']));
                                                    $secID = urlencode(trim($row['secID']));
                                                    $gradelvlID = urlencode(trim($row['gradelvlID']));
                                                    $programID = urlencode(trim($row['programID']));
                                                    $studName = urlencode(trim($row['lname']) . ', ' . trim($row['fname']) . ' ' . trim($row['mname']));
                                                    $subjectID = urlencode(0); // Hardcoded as 0
                                                    $ayID = urlencode(trim($row['ayID']));
                                                    $facultyID = urlencode(trim($facultyID));
                                                    $deptID = urlencode(trim($deptID));

                                                    $url = "students_subj.php?studID=$studID&semID=$semID&secID=$secID&gradelvlID=$gradelvlID&programID=$programID&subjectID=$subjectID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID";
                                                ?>

                                                    <a class="btn btn-primary btn-sm" type="button"
                                                        style="font-size: 13px; height: 31px"
                                                        data-bs-toggle="tooltip" title="Enroll Subjects" 
                                                        href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="bi bi-book me-1"></i> Subjects
                                                    </a>

                                                    <?php if($secAyName != $activeAY):?>
                                                    <?php else:?>
                                                        <button class="btn btn-danger delete-btn btn-sm" 
                                                        data-bs-toggle="tooltip"
                                                        title="Unenroll Student"
                                                        data-enroll-id="<?php echo $row['enrollID']; ?>" 
                                                        data-stud-id="<?php echo $row['studID']; ?>">
                                                        <i class="bi bi-person-dash-fill"></i>
                                                    </button>
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main><!-- End #main -->
    
 
  <?php require_once"support/footer.php"?>            

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <script src="assets/sweetalert2.all.min.js"></script>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
 
  <script src="assets/js/main.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

  <script>
function handleDeleteButtonClick(enrollID, studName, studID) {
    Swal.fire({
        title: 'Confirmation Required',
        text: 'You are about to delete ' + studName + ' ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_student.php',
                method: 'POST',
                data: { enrollID: enrollID }, 
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deletion Successful',
                        text: 'The student ' + studName + ' has been successfully deleted.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload(); 
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete student. Please try again later.'
                    });
                }
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault(); 
            var enrollID = this.getAttribute('data-enroll-id');
            var studID = this.getAttribute('data-stud-id');
            var studName = this.closest('tr').querySelector('td:nth-child(4)').innerText; 
            handleDeleteButtonClick(enrollID, studName, studID);
        });
    });
});
</script>


<?php

// Check if the section is semester-based or non-semester based using $deptID
$isSemesterSection = ($deptID == 3);  // If $deptID is 3, it's a semester-based section

// Check for success parameter in the URL
if (isset($_GET['success']) && $_GET['success'] == 'true'):

    // Fetch the studIDs of the enrolled students
    $enrolledStudIDs = [];
    $unregisteredStudents = [];
    try {
        $query = "SELECT studID FROM section_students WHERE secID = :secID AND gradelvlID = :gradelvlID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
        $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $enrolledStudIDs[] = $row['studID'];
        }
    } catch (Throwable $e) {
        echo "Error fetching enrolled students: " . $e->getMessage();
    }

    // Convert the studIDs to a comma-separated string
    $studIDsString = implode(',', $enrolledStudIDs);
    $unregisteredList = !empty($unregisteredStudents) ? implode(', ', $unregisteredStudents) : 'No unregistered students.';

?>
    <script>
        // Fetch the 'unregistered' parameter from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const unregistered = urlParams.get('unregistered');  // Get unregistered students from the URL

        // Show the success SweetAlert
        Swal.fire({
            title: 'Students Enrolled Successfully!',
            text: 'Do you want to enroll subjects for the students you enrolled earlier?',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Enroll Subjects',
            cancelButtonText: 'Don\'t Enroll',
        }).then((result) => {
            // Clear the 'success' parameter from the URL
            let url = new URL(window.location.href);
            url.searchParams.delete('success');
            history.replaceState(null, '', url.toString());

            // Handle the result from SweetAlert
            let enrollURL = 'enroll_subjects.php?secID=<?php echo $secID; ?>&gradelvlID=<?php echo $gradelvlID; ?>&ayID=<?php echo $ayID; ?>&facultyID=<?php echo $facultyID; ?>&programID=<?php echo $programID; ?>&deptID=<?php echo $deptID; ?>&ayName=<?php echo $secAyName; ?>&studIDs=<?php echo $studIDsString; ?>';

            // Check if it's a semester-based section and include semID
            <?php if ($isSemesterSection): ?>
                enrollURL += '&semID=<?php echo $semID; ?>';
            <?php endif; ?>

            // If it's a non-semester section, don't include semID and programID
            <?php if (!$isSemesterSection): ?>
                enrollURL += '&nonSem=true'; // flag for non-semester sections
            <?php endif; ?>

            // Redirect to enroll subjects page or show unregistered students info
            if (result.isConfirmed) {
                document.getElementById("loader").style.display = "flex";
                window.location.href = enrollURL;
            }

            // Show the unregistered students information
            if (unregistered) {
                Swal.fire({
                    title: 'Students Not Registered in the System',
                    html: `
                        <p><strong>The following students have not been registered in the system yet:</strong></p>
                        <ul>
                            ${unregistered.split(',').map(lrn => `<li>LRN: ${lrn}</li>`).join('')}
                        </ul>
                        <p>Please ensure their information is updated in the system before proceeding.</p>
                    `,
                    icon: 'info',
                });
            }
        });
    </script>
<?php endif; ?>


<script>
    // Function to get query parameter value from the URL
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Function to handle SweetAlert popups for various cases
    function handleAlert(param, title, text, icon) {
        if (getQueryParam(param) === 'true') {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = new URL(window.location.href);
                    url.searchParams.delete(param);

                    history.replaceState(null, '', url.toString());

                    location.reload();
                }
            });
        }
    }

    // Handle each case
    handleAlert('ayNameNotMatched', 'A.Y. Not Matched!', 'The Excel file does not match the A.Y.', 'warning');
    handleAlert('noFile', 'No File Uploaded!', 'Please upload a valid Excel file.', 'error');
    handleAlert('facNotMatched', 'Faculty Not Matched!', 'The Excel file does not match the section adviser.', 'warning');
    handleAlert('secNotMatched', 'Section Not Matched!', 'The Excel file does not match the selected section.', 'warning');
    handleAlert('lvlNotMatched', 'Grade Level Not Matched!', 'The Excel file does not match the selected grade level.', 'warning');
    handleAlert('progNotMatched', 'Program Not Matched!', 'The Excel file does not match the selected program.', 'warning');
    handleAlert('semNotMatched', 'Semester Not Matched!', 'The Excel file does not match the selected semester.', 'warning');
</script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("loader").style.display = "none";
});

document.querySelector('.enrollSHS-stud-btn').addEventListener('click', function (e) {
    e.preventDefault(); 

    Swal.fire({
        title: 'Select Student Type',
        text: 'What type of student will you enroll?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Regular',
        cancelButtonText: 'Irregular',
        focusCancel: true,
        showCloseButton: true,
        allowOutsideClick: false,
        animation: true
    }).then((result) => {
        let studentType = ''; // Default value if no selection is made

        if (result.dismiss === Swal.DismissReason.close) {
            return; 
        }

        // Detect what button was clicked
        if (result.isConfirmed) {
            studentType = 'regular'; // Regular is selected
        } else if (result.isDismissed) {
            studentType = 'irregular'; // Irregular is selected
        }

        // Trigger the modal based on student type selection
        if (studentType) {
            var modalId = studentType === 'regular' ? 'addStudentSec' : 'addIrregular'; // Choose modal ID based on selection

            // Create the modal object
            var modal = new bootstrap.Modal(document.getElementById(modalId));

            // Set the data attributes dynamically to the modal based on user selection
            document.getElementById(modalId).setAttribute('data-student-type', studentType);

            // Now open the modal
            modal.show();
        }
    });
});

</script>

<?php include"modals/studentM.php"?>                                                

</body>


</html>