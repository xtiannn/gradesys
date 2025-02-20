<?php
    include 'session.php';

    require_once "includes/config.php";
    require_once "fetch/fetch_activeAY.php";
    require_once("../faculty/fetch/permission.php");




    $secID = $_GET['secID'];
    $programID = $_GET['programID'];
    $gradelvlID = $_GET['gradelvlID'];
    $ayID = $_GET['ayID'];
    $facultyID = $_GET['facultyID'];
    $deptID = $_GET['deptID'];
    $studID = $_GET['studID'];

    try {
        $sqlStudName = "SELECT CONCAT(lname, ', ', fname, ' ', mname) AS studName, photo, lrn
                        FROM students WHERE studID = :studID";
        $stmtStudname = $conn->prepare($sqlStudName);
        $stmtStudname->bindParam(':studID', $_GET['studID'], PDO::PARAM_INT);
        $stmtStudname->execute();

        $resultStudName = $stmtStudname->fetch(PDO::FETCH_ASSOC);

        $studName = $resultStudName['studName'] ?? 'No student Found';
        $photo = $resultStudName['photo'];
        $lrn = $resultStudName['lrn'] ?? '';
    } catch (\Throwable $e) {
        echo 'ERROR FETCHING student name'.$e->getMessage();
    }

    try {
        $sectionAyName = "SELECT ayName, semID FROM sections WHERE secID = :secID";
        $stmtSecAyName = $conn->prepare($sectionAyName);
        $stmtSecAyName->bindParam(':secID', $secID, PDO::PARAM_INT);
        $stmtSecAyName->execute();

        $resultAyName = $stmtSecAyName->fetch(PDO::FETCH_ASSOC);
        $secAyName = $resultAyName['ayName'];
        $secSemID = $resultAyName['semID'] ?? NULL;
    } catch (\Throwable $th) {
        echo "Error fetching AY Name: " . $e->getMessage();
    }

    try {
        $sqlSecName = "SELECT s.ayName, s.secName, gl.gradelvlcode, s.gradelvlID, s.programID, p.programcode
        FROM sections s
        JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
        LEFT JOIN programs p ON s.programID = p.programID
        WHERE s.secID = :secID
        AND (s.gradelvlID = :gradelvlID OR s.programID = :programID)";
        $stmtSecName = $conn->prepare($sqlSecName);
        $stmtSecName->bindParam(':secID', $_GET['secID'], PDO::PARAM_STR);
        $stmtSecName->bindParam(':gradelvlID', $_GET['gradelvlID'], PDO::PARAM_INT);
        $stmtSecName->bindParam(':programID', $_GET['programID'], PDO::PARAM_INT);
        $stmtSecName->execute();

        $resultSecName = $stmtSecName->fetch(PDO::FETCH_ASSOC);
        
        $programname = $resultSecName['programcode'] ?? '';
        $sectionname = ucwords(strtolower($resultSecName['secName'] ?? ''));
        $gradelvlname = $resultSecName['gradelvlcode'] ?? '';
        $secAy = $resultSecName['ayName'] ?? '';

        $ConcatedSecName = "$gradelvlname - $sectionname";
    } catch (\Throwable $e) {
        echo 'ERROR fetching sec name'.$e->getMessage();
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

  <style>
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
    margin-left: 8px
    }
</style>
</head>
<body>
  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>
  <main id="main" class="main">
        <section class="section">
            <div class="custom-container">
                    <div class="row">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="section_builder.php?deptID=<?php echo urlencode(trim($deptID)); ?>">Sections</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <?php if($deptID == 3):?>
                                        <?php
                                            $semID = urlencode(trim($_GET['semID'] ?? ''));
                                            $secName = urlencode(trim($_GET['secName'] ?? ''));
                                            $secID = urlencode(trim($_GET['secID'] ?? ''));
                                            $programID = urlencode(trim($_GET['programID'] ?? ''));
                                            $gradelvlID = urlencode(trim($_GET['gradelvlID'] ?? ''));
                                            $ayID = urlencode(trim($_GET['ayID'] ?? ''));
                                            $facultyID = urlencode(trim($_GET['facultyID'] ?? ''));
                                            $deptID = urlencode(trim($_GET['deptID'] ?? ''));

                                            $url = "enrolled_students.php?semID=$semID&secName=$secName&secID=$secID&programID=$programID&gradelvlID=$gradelvlID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID";
                                        ?>
                                    <?php else:?>
                                        <?php
                                            $secName = urlencode(trim($_GET['secName'] ?? ''));
                                            $secID = urlencode(trim($_GET['secID'] ?? ''));
                                            $gradelvlID = urlencode(trim($_GET['gradelvlID'] ?? ''));
                                            $ayID = urlencode(trim($_GET['ayID'] ?? ''));
                                            $facultyID = urlencode(trim($_GET['facultyID'] ?? ''));
                                            $deptID = urlencode(trim($_GET['deptID'] ?? ''));

                                            $url = "enrolled_students.php?secName=$secName&secID=$secID&gradelvlID=$gradelvlID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID";
                                        ?>
                                    <?php endif;?>

                                    <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>">
                                        Manage Students
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Subjects</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-start">
                                        <div class="student-photo-container me-3">
                                            <img src="<?php echo !empty($photo) ? '../gmsn/uploads/photo/' . $photo : '../gmsn/assets/img/user.png'; ?>" alt="Student Photo" class="img-fluid" style="height: 90px; width: auto; object-fit: cover;">
                                        </div>
                                        <div class="mt-0">
                                            <div class="d-flex">
                                                <h6 class="custom-card-title fw-normal" style="font-size: 14px; margin: 0; width: 80px;">Name:</h6>
                                                <span class="custom-card-title" style="font-weight: 700; font-size: 14px; margin: 0;"><?php echo ucwords(strtolower( $studName)); ?></span>
                                            </div>
                                            <div class="d-flex">
                                                <h6 class="custom-card-title fw-normal" style="font-size: 14px; margin: 0; width: 80px;">LRN:</h6>
                                                <span class="custom-card-title" style="font-weight: 700; font-size: 14px; margin: 0;"><?php echo $lrn; ?></span>
                                            </div>
                                            <div class="programDiv" style="display: <?php echo ($deptID==3) ? 'block' : 'none'?>;">
                                                <div class="d-flex">
                                                    <h6 class="custom-card-title fw-normal" style="font-size: 14px; margin: 0; width: 80px;">Program:</h6>
                                                    <span class="custom-card-title" style="font-weight: 700; font-size: 14px; margin: 0;"><?php echo $programname; ?></span>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <h6 class="custom-card-title fw-normal" style="font-size: 14px; margin: 0; width: 80px;">Section:</h6>
                                                <span class="custom-card-title" style="font-weight: 700; font-size: 14px; margin: 0;"><?php echo $ConcatedSecName; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($deptID == 3):?>
                                        <?php if(($semID != $activeSem) || ($secAyName != $activeAY)):?>
                                        <?php else:?>
                                            <div class="d-flex align-items-center mb-0">
                                                <button href="#" class="btn btn-primary btn-sm me-2 mb-4" data-bs-toggle="modal" data-bs-target="#enrollSubjects">
                                                    <i class="bi bi-book me-1"></i> Enroll Subject
                                                </button> 
                                                <form action="excel/test2.php" method="POST" enctype="multipart/form-data" class="me-2">
                                                    <div class="input-group mt-1">
                                                        <!-- Hidden file input -->
                                                        <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control form-control-sm mt-3" style="display: none;" />
                                                        
                                                        <!-- Label triggers file input -->
                                                        <label for="file" class="btn btn-outline-success btn-sm">
                                                            Import Grades
                                                        </label>

                                                        <!-- Hidden fields to pass additional data -->
                                                        <input type="hidden" name="facultyID" value="<?php echo $facultyID?>">
                                                        <input type="hidden" name="secID" value="<?php echo $secID?>">
                                                        <input type="hidden" name="semID" value="<?php echo $semID?>">
                                                        <input type="hidden" name="deptID" value="<?php echo $deptID?>">
                                                        <input type="hidden" name="studID" value="<?php echo $studID?>">
                                                        <input type="hidden" name="activeAY" value="<?php echo $activeAY?>">
                                                        
                                                        <!-- Submit button -->
                                                        <button type="submit" name="submit" style="height: 31px" class="btn btn-success btn-sm">
                                                            <i class="bi bi-upload"></i>
                                                        </button>
                                                    </div>
                                                    <!-- File name preview -->
                                                    <div id="fileNamePreview" style="font-size: 12px; color: #555; visibility: hidden; height: 20px; line-height: 20px;"></div>
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
                                        <?php endif?>
                                    <?php else:?>
                                        <!-- fetching the section AY NAME -->
                                        <?php if($secAyName != $activeAY):?>
                                        <?php else:?>
                                            <div class="d-flex align-items-center mb-0">
                                                <button href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#enrollSubjects">
                                                    <i class="bi bi-person-add"></i> Enroll Subject
                                                </button> 



                                                <form action="excel/test3.php" class="ms-2 mt-4"  method="POST" enctype="multipart/form-data" class="me-2">
                                                    <div class="input-group mt-1">
                                                        <!-- Hidden file input -->
                                                        <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control form-control-sm mt-3" style="display: none;" />
                                                        
                                                        <!-- Label triggers file input -->
                                                        <label for="file" class="btn btn-outline-success btn-sm">
                                                            Import Grades
                                                        </label>

                                                        <!-- Hidden fields to pass additional data -->
                                                        <input type="hidden" name="facultyID" value="<?php echo $facultyID?>">
                                                        <input type="hidden" name="secID" value="<?php echo $secID?>">
                                                        <input type="hidden" name="semID" value="<?php echo $semID?>">
                                                        <input type="hidden" name="deptID" value="<?php echo $deptID?>">
                                                        <input type="hidden" name="studID" value="<?php echo $studID?>">
                                                        <input type="hidden" name="activeAY" value="<?php echo $activeAY?>">
                                                        
                                                        <!-- Submit button -->
                                                        <button type="submit" name="submit" style="height: 31px" class="btn btn-success btn-sm">
                                                            <i class="bi bi-upload"></i>
                                                        </button>
                                                    </div>
                                                    <!-- File name preview -->
                                                    <div id="fileNamePreview" style="font-size: 12px; color: #555; visibility: hidden; height: 20px; line-height: 20px;"></div>
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



                                                <!-- <form action="excel/export_grades.php" method="post">
                                                <input type="hidden" name="studID" value="<?php //echo $studID?>">
                                                    <input type="hidden" name="semID" value="<?php //echo $semID?>">
                                                    <input type="hidden" name="secID" value="<?php //echo $secID?>">
                                                    <input type="hidden" name="gradelvlID" value="<?php //echo $gradelvlID?>">
                                                    <input type="hidden" name="programID" value="<?php //echo $programID?>">
                                                    <input type="hidden" name="subjectID" value="<?php //echo $subjectID ?? NULL?>">
                                                    <input type="hidden" name="ayID" value="<?php //echo $ayID?>">
                                                    <input type="hidden" name="facultyID" value="<?php //echo $facultyID?>">
                                                    <input type="hidden" name="deptID" value="<?php //echo $deptID?>">
                                                    <button type="submit" id="export" name="export_grades" class="btn btn-success btn-sm ms-2">
                                                        <i class="bi bi-download"></i> Export Grades
                                                    </button>
                                                </form>
                                                <script>
                                                    document.getElementById('export').addEventListener('click', function() {
                                                        window.location.href = 'excel/export_grades.php?export_grades=true';
                                                    });
                                                </script> -->
                                            </div>
                                        <?php endif?>
                                    <?php endif?>
                                </div>
                                <div class="card-body">
                                    <?php if($deptID == 3):?>
                                        <table class="table table-striped table-bordered datatable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">A.Y.</th>
                                                    <th class="text-center">Term</th>
                                                    <th>Code</th>
                                                    <th>Subject</th>
                                                    <th class="text-center"><?php echo ($_GET['semID'] == 1) ? 'Q1' : 'Q3' ?></th>
                                                    <th class="text-center"><?php echo ($_GET['semID'] == 1) ? 'Q2' : 'Q4' ?></th>
                                                    <th class="text-center" style="white-space: no-wrap">Final Grade</th>
                                                    <th class="text-center" style="width: 150px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $programID = $_GET['programID'];
                                                $gradelvlID = $_GET['gradelvlID'];
                                                $semID = $_GET['semID'];
                                                $studID = $_GET['studID'];
                                                $subjectID = $_GET['subjectID'];
                                                $secID = $_GET['secID'];

                                                require_once("includes/config.php");

                                                $query = "SELECT DISTINCT ss.*, 
                                                            s.subjectcode as code,
                                                            s.subjectname as subject,
                                                            st.lrn,
                                                            CONCAT(st.lname, ', ', st.fname, ' ', st.mname) AS studname,
                                                            sg.grade,
                                                            sg.grade2,
                                                            sg.fgrade,
                                                            ss.ayName,
                                                            sm.semCode
                                                        FROM section_students ss
                                                        JOIN subjects s ON s.subjectID = ss.subjectID
                                                        JOIN students st ON st.studID = ss.studID
                                                        JOIN semester sm ON sm.semID = ss.semID
                                                        LEFT JOIN student_grades sg ON sg.studID = ss.studID AND sg.subjectID = ss.subjectID AND sg.secID = :secID
                                                        WHERE ss.studID = :studID 
                                                            AND ss.semID = :semID 
                                                            AND ss.secID = :secID
                                                            AND ss.programID = :programID 
                                                            AND ss.gradelvlID = :gradelvlID 
                                                            AND ss.subjectID != :subjectID
                                                            ORDER BY subjectname ASC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindValue(':studID', $studID, PDO::PARAM_INT);
                                                $stmt->bindValue(':semID', $semID, PDO::PARAM_INT);
                                                $stmt->bindValue(':secID', $secID, PDO::PARAM_INT);
                                                $stmt->bindValue(':programID', $programID, PDO::PARAM_INT);
                                                $stmt->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                                $stmt->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                                                $stmt->execute();

                                                $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $count = 1;
                                                foreach ($curriculum as $row) :

                                    
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $count++; ?>.</td>
                                                        <td class="text-center" style="white-space: nowrap;"><?php echo $row['ayName']; ?></td>
                                                        <td class="text-center"><?php echo $row['semCode']; ?></td>
                                                        <td><?php echo $row['code']; ?></td>
                                                        <td><?php echo $row['subject']; ?></td>
                                                        <td class="text-center"
                                                            contenteditable="true" 
                                                            data-field="grade" data-id="<?php echo $row['enrollID']; ?>">
                                                            <?php echo ($row['grade'] != 0) ? $row['grade'] : ''; ?>
                                                        </td>
                                                        <td class="text-center"
                                                            contenteditable="true" data-field="grade2" data-id="<?php echo $row['enrollID']; ?>">
                                                            <?php echo ($row['grade2'] != 0) ? $row['grade2'] : ''; ?>
                                                        </td>
                                                        <td class="text-center fw-bold" <?php if ($row['fgrade'] < 75) echo 'style="color: red;"'; ?>>
                                                            <?php 
                                                                if($row['grade'] != 0 && $row['grade2'] != 0){
                                                                    echo $row['fgrade'];
                                                                }elseif((empty($row['grade']) && !empty($row['grade2'])) || (empty($row['grade']) && empty($row['grade2']) && ($row['ayName'] != $activeAY))){
                                                                    echo '<span class="text-warning">INC</span>';
                                                                }else{

                                                                }
                                                            ?>
                                                        </td>

                                                        <td class="text-center">
                                                        <button type="button" 
                                                            class="btn btn-success save-btn btn-sm" 
                                                            data-ay-name="<?php echo $row['ayName']; ?>"
                                                            data-sec-id="<?php echo $row['secID']; ?>"
                                                            data-enroll-id="<?php echo $row['enrollID']; ?>"
                                                            data-stud-id="<?php echo $row['studID']; ?>"
                                                            data-subject-id="<?php echo $row['subjectID']; ?>"
                                                            data-sem-id="<?php echo $row['semID']; ?>"
                                                            data-gradelvl-id="<?php echo $row['gradelvlID']; ?>" 
                                                            data-faculty-id="<?php echo $row['facultyID']; ?>"   
                                                            data-dept-id="<?php echo $deptID; ?>"         
                                                            data-grade-first="<?php echo $row['grade']; ?>"
                                                            data-grade-second="<?php echo $row['grade2']; ?>"
                                                            data-fgrade="<?php echo $row['fgrade']; ?>"
                                                        >
                                                            Save <i class="bi bi-save"></i>
                                                        </button>
                                                            <button 
                                                            style="<?php echo ($secAyName != $activeAY || $semID != $activeSem) ? 'display: none' : '' ?>"
                                                            class="btn btn-danger delete-btn btn-sm" 
                                                            data-bs-toggle="tooltip"
                                                            title="Unenroll Subject"
                                                            data-enroll-id="<?php echo $row['enrollID']; ?>" <?php echo (
                                                                            ($row['grade'] != NULL) || 
                                                                            ($row['grade2'] != NULL)) 
                                                                        ? 'disabled' : ''?>>
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else:?>
                                        <table class="table table-striped table-bordered datatable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">A.Y.</th>
                                                    <th>Code</th>
                                                    <th>Subject</th>
                                                    <th class="text-center">Q1</th>
                                                    <th class="text-center">Q2</th>
                                                    <th class="text-center">Q3</th>
                                                    <th class="text-center">Q4</th>
                                                    <th class="text-center" style="white-space: no-wrap">Final Grade</th>
                                                    <th class="text-center" style="width: 150px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $programID = $_GET['programID'] ?? '';
                                                $gradelvlID = $_GET['gradelvlID'] ?? '';
                                                $semID = $_GET['semID'] ?? '';
                                                $studID = $_GET['studID'] ?? '';
                                                $subjectID = $_GET['subjectID'] ?? '';
                                                $secID = $_GET['secID'] ?? '';

                                                require_once("includes/config.php");

                                                $query = "SELECT DISTINCT ss.*, 
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
                                                            ORDER BY subjectname ASC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindValue(':studID', $studID, PDO::PARAM_INT);
                                                $stmt->bindValue(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                                $stmt->bindValue(':subjectID', $subjectID, PDO::PARAM_INT);
                                                $stmt->bindValue(':secID', $secID, PDO::PARAM_INT);
                                                $stmt->execute();
                                                $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $count = 1;
                                                foreach ($curriculum as $row) :
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $count++; ?>.</td>
                                                        <td class="text-center" style="white-space: nowrap;"><?php echo $row['ayName']; ?></td>
                                                        <td><?php echo $row['code']; ?></td>
                                                        <td><?php echo $row['subject']; ?></td>
                                                        <td class="text-center"
                                                            contenteditable="true" data-field="grade" data-id="<?php echo $row['enrollID']; ?>">
                                                            <?php echo ($row['grade'] != 0) ? $row['grade'] : ''; ?>
                                                        </td>
                                                        <td class="text-center" 
                                                            contenteditable="true" data-field="grade2" data-id="<?php echo $row['enrollID']; ?>">
                                                            <?php echo ($row['grade2'] != 0) ? $row['grade2'] : ''; ?>
                                                        </td>
                                                        <td class="text-center" 
                                                            contenteditable="true" data-field="grade3" data-id="<?php echo $row['enrollID']; ?>">
                                                            <?php echo ($row['grade3'] != 0) ? $row['grade3'] : ''; ?>
                                                        </td>
                                                        <td class="text-center" 
                                                            contenteditable="true" data-field="grade4" data-id="<?php echo $row['enrollID']; ?>">
                                                            <?php echo ($row['grade4'] != 0) ? $row['grade4'] : ''; ?>
                                                        </td>
                                                        <td class="text-center fw-bold" <?php if ($row['fgrade'] < 75) echo 'style="color: red;"'; ?>>
                                                            <?php echo ($row['grade'] != 0 && $row['grade2'] != 0) ? $row['fgrade'] : ''; ?>
                                                        </td>

                                                        <td class="text-center">
                                                        <button type="button" class="btn btn-success save-btn btn-sm" 
                                                            data-ay-name="<?php echo $row['ayName']; ?>"
                                                            data-sec-id="<?php echo $row['secID']; ?>"
                                                            data-enroll-id="<?php echo $row['enrollID']; ?>"
                                                            data-stud-id="<?php echo $row['studID']; ?>"
                                                            data-subject-id="<?php echo $row['subjectID']; ?>"
                                                            data-gradelvl-id="<?php echo $row['gradelvlID']; ?>" 
                                                            data-faculty-id="<?php echo $row['facultyID']; ?>"   
                                                            data-dept-id="<?php echo $deptID; ?>"         
                                                            data-grade-first="<?php echo $row['grade']; ?>"
                                                            data-grade-second="<?php echo $row['grade2']; ?>"
                                                            data-fgrade="<?php echo $row['fgrade']; ?>"
                                                        >
                                                            Save <i class="bi bi-save"></i>
                                                        </button>
                                                            <button 
                                                            style="<?php echo ($secAyName != $activeAY) ? 'display: none' : '' ?>"
                                                            class="btn btn-danger delete-btn btn-sm" 
                                                            data-bs-toggle="tooltip"
                                                            title="Unenroll Subject"
                                                            data-enroll-id="<?php echo $row['enrollID']; ?>" <?php echo (
                                                                            ($row['grade'] != NULL) || 
                                                                            ($row['grade2'] != NULL)) 
                                                                        ? 'disabled' : ''?>>
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
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
            </div>
        </section>

        
       <?php include"modals/studentM.php"?>                                                

    </main><!-- End #main -->
    
 
  <?php require_once"support/footer.php"?>            

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <script src="assets/sweetalert2.all.min.js"></script>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
 
  <script>
    $(document).ready(function() {
        $(document).on('click', '.save-btn', function() {
            var ayName = $(this).data('ay-name');
            var secID = $(this).data('sec-id');
            var studID = $(this).data('stud-id');
            var enrollID = $(this).data('enroll-id');
            var subjectID = $(this).data('subject-id');
            var semID = $(this).data('sem-id');
            var gradelvlID = $(this).data('gradelvl-id');
            var facultyID = $(this).data('faculty-id');
            var deptID = $(this).data('dept-id');

            var newGradeFirst = $(this).closest('tr').find('td[data-field="grade"]').text().trim();
            var newGradeSecond = $(this).closest('tr').find('td[data-field="grade2"]').text().trim();
            var newGradeThird = $(this).closest('tr').find('td[data-field="grade3"]').text().trim();
            var newGradeFourth = $(this).closest('tr').find('td[data-field="grade4"]').text().trim();

            // Validation function
            function isValidGrade(grade) {
                return grade == 0 || (!isNaN(grade) && grade >= 60 && grade <= 100);
            }

            // Validate grades
            if (!isValidGrade(newGradeFirst)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Grade',
                    text: 'Please enter a valid grade between 60.00 and 100.00 or leave it empty to clear.',
                });
                return;
            }
            if (!isValidGrade(newGradeSecond)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Grade',
                    text: 'Please enter a valid grade between 60.00 and 100.00 or leave it empty to clear.',
                });
                return;
            }

            if (!semID) { // Only validate grade3 and grade4 if semID is not set
                if (!isValidGrade(newGradeThird)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Grade',
                        text: 'Please enter a valid grade between 60.00 and 100.00 or leave it empty to clear.',
                    });
                    return;
                }
                if (!isValidGrade(newGradeFourth)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Grade',
                        text: 'Please enter a valid grade between 60.00 and 100.00 or leave it empty to clear.',
                    });
                    return;
                }
            }

            // AJAX function to save grades
            function saveGrade(field, value) {
                $.ajax({
                    url: 'save_grade_ajax.php',
                    method: 'POST',
                    data: {
                        secID: secID,
                        ayName: ayName,
                        studID: studID,
                        enrollID: enrollID,
                        subjectID: subjectID,
                        semID: semID,
                        gradelvlID: gradelvlID,
                        facultyID: facultyID,
                        deptID: deptID,
                        field: field,
                        value: value === "" ? null : value // Send null if the grade is empty
                    },
                    success: function(response) {
                        console.log(`${field} saved:`, response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Grade Saved',
                            text: 'Grade saved successfully.',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            location.reload(); // Reload the page after successful save
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `Error saving ${field}.`,
                        });
                    }
                });
            }

            // Save grades
            saveGrade('grade', newGradeFirst);
            saveGrade('grade2', newGradeSecond);

            if (!semID) { // Only save grade3 and grade4 if semID is not set
                saveGrade('grade3', newGradeThird);
                saveGrade('grade4', newGradeFourth);
            }
        });
    });
</script>





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
function handleDeleteButtonClick(enrollID, studName) {
    Swal.fire({
        title: 'Confirmation Required',
        text: 'You are about to unenroll ' + studName + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Unenroll'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_subject.php',
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
                        title: 'Oops...',
                        text: 'Failed to delete subject. Please try again later.'
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
            var studName = this.closest('tr').querySelector('td:nth-child(3)').innerText; 
            handleDeleteButtonClick(enrollID, studName);
        });
    });
});
</script>




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
    handleAlert('studNotMatched', 'Student ID Not Matched!', 'The Excel file does not match the Student ID', 'warning');
    handleAlert('noFile', 'No File Uploaded!', 'Please upload a valid Excel file.', 'error');
    handleAlert('facNotMatched', 'Faculty Not Matched!', 'The Excel file does not match the section adviser.', 'warning');
    handleAlert('secNotMatched', 'Section Not Matched!', 'The Excel file does not match the selected section.', 'warning');
    handleAlert('lvlNotMatched', 'Grade Level Not Matched!', 'The Excel file does not match the selected grade level.', 'warning');
    handleAlert('progNotMatched', 'Program Not Matched!', 'The Excel file does not match the selected program.', 'warning');
    handleAlert('semNotMatched', 'Semester Not Matched!', 'The Excel file does not match the selected semester.', 'warning');
</script>


</body>


</html>