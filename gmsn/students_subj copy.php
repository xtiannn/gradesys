<?php
    include 'session.php';

    require_once "includes/config.php";
    require_once "fetch/fetch_activeAY.php";



    $secID = $_GET['secID'];
    $programID = $_GET['programID'];
    $gradelvlID = $_GET['gradelvlID'];
    $ayID = $_GET['ayID'];
    $facultyID = $_GET['facultyID'];
    $deptID = $_GET['deptID'];

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
        $sqlSecName = "SELECT s.secName, gl.gradelvlcode, s.gradelvlID, s.programID, p.programcode
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
                                                <button href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#enrollSubjects">
                                                    <i class="bi bi-person-add"></i> Enroll Subject
                                                </button> 
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

                                                $query = "SELECT ss.*, 
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
                                                $count = 0;
                                                foreach ($curriculum as $row) :

                                    
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo ++$count; ?>.</td>
                                                        <td class="text-center" style="white-space: nowrap;"><?php echo $row['ayName']; ?></td>
                                                        <td class="text-center"><?php echo $row['semCode']; ?></td>
                                                        <td><?php echo $row['code']; ?></td>
                                                        <td><?php echo $row['subject']; ?></td>
                                                        <td class="text-center" style="<?php echo ($row['grade'] < 75) ? 'color: red;' : ''; ?>">
                                                            <?php echo ($row['grade'] != 0) ? $row['grade'] : ''; ?>
                                                        </td>
                                                        <td class="text-center" style="<?php echo ($row['grade2'] < 75) ? 'color: red;' : ''; ?>">
                                                            <?php echo ($row['grade2'] != 0) ? $row['grade2'] : ''; ?>
                                                        </td>
                                                        <td class="text-center fw-bold" <?php if ($row['fgrade'] < 75) echo 'style="color: red;"'; ?>>
                                                            <?php echo ($row['grade'] != 0 && $row['grade2'] != 0) ? $row['fgrade'] : ''; ?>
                                                        </td>

                                                        <td class="text-center">
                                                            <button type="button" 
                                                            style="font-size: 13px; height: 31px"
                                                            class="btn btn-success grade-btn btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#inputGradeModalSec" 
                                                                data-stud-id="<?php echo $row['studID']; ?>"
                                                                data-enroll-id="<?php echo $row['enrollID']; ?>"
                                                                data-subject-id="<?php echo $row['subjectID']; ?>"
                                                                data-subject-name="<?php echo $row['subject']; ?>"
                                                                data-grade-first="<?php echo $row['grade']; ?>"
                                                                data-grade-second="<?php echo $row['grade2']; ?>"
                                                                data-sem-id="<?php echo $row['semID']; ?>"
                                                                data-gradelvl-id="<?php echo $row['gradelvlID']; ?>"
                                                                data-program-id="<?php echo $row['programID']; ?>"
                                                                data-ay-id="<?php echo $row['ayID']; ?>"
                                                                data-stud-name="<?php echo $row['studname']; ?>"
                                                                data-dept-id="<?php echo $deptID; ?>"
                                                                data-faculty-id="<?php echo $row['facultyID']; ?>"
                                                                data-stud-lrn="<?php echo $row['lrn']; ?>"
                                                                >
                                                                Grade <i class="bi bi-plus-lg"></i> 
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
                                                <?php
                                                    $sqlSem = "SELECT semID FROM academic_year";
                                                    $semStmt = $conn->prepare($sqlSem);
                                                    $semStmt->execute();
                                                    $semResult = $semStmt->fetch(PDO::FETCH_ASSOC);

                                                    if($semResult){
                                                        $currentSemID = $semResult['semID'];
                                                    }else{
                                                        $currentSemID = null;
                                                    }
                                                ?>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">A.Y.</th>
                                                    <th>Code</th>
                                                    <th>Subject</th>
                                                    <th class="text-center" style="white-space: no-wrap">Q1</th>
                                                    <th class="text-center" style="white-space: no-wrap">Q2</th>
                                                    <th class="text-center" style="white-space: no-wrap">Q3</th>
                                                    <th class="text-center" style="white-space: no-wrap">Q4</th>
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

                                                $query = "SELECT ss.*, 
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
                                                        <td class="text-center" style="white-space: nowrap"><?php echo $row['ayName']; ?></td>
                                                        <td><?php echo $row['code']; ?></td>
                                                        <td><?php echo $row['subject']; ?></td>
                                                        <td class="text-center" style="<?php echo ($row['grade'] < 75) ? 'color: red;' : ''; ?>">
                                                            <?php echo ($row['grade'] != 0) ? $row['grade'] : ''; ?>
                                                        </td>
                                                        <td class="text-center" style="<?php echo ($row['grade2'] < 75) ? 'color: red;' : ''; ?>">
                                                            <?php echo ($row['grade2'] != 0) ? $row['grade2'] : ''; ?>
                                                        </td>

                                                        <td class="text-center" style="<?php echo ($row['grade3'] < 75) ? 'color: red;' : ''; ?>">
                                                            <?php echo ($row['grade3'] != 0) ? $row['grade3'] : ''; ?>
                                                        </td>
                                                        <td class="text-center" style="<?php echo ($row['grade4'] < 75) ? 'color: red;' : ''; ?>">
                                                            <?php echo ($row['grade4'] != 0) ? $row['grade4'] : ''; ?>
                                                        </td>

                                                        <td class="text-center fw-bold" <?php if ($row['fgrade'] < 75) echo 'style="color: red;"'; ?>>
                                                            <?php echo ($row['grade'] != 0 && $row['grade2'] != 0 && $row['grade3'] != 0 && $row['grade4'] != 0) ? $row['fgrade'] : ''; ?>
                                                        </td>
                                                        
                                                        <td class="text-center">
                                                            <button type="button" 
                                                            style="font-size: 13px; height: 31px"
                                                            class="btn btn-success grade-btn btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#inputGradeModalSec" 
                                                                data-stud-id="<?php echo $row['studID']; ?>"
                                                                data-enroll-id="<?php echo $row['enrollID']; ?>"
                                                                data-subject-id="<?php echo $row['subjectID']; ?>"
                                                                data-subject-name="<?php echo $row['subject']; ?>"
                                                                data-grade-first="<?php echo ($activeSem == 1) ? $row['grade'] : $row['grade3']; ?>"
                                                                data-grade-second="<?php echo ($activeSem == 1) ? $row['grade2'] : $row['grade4']; ?>"
                                                                data-sem-id="<?php echo $row['semID']; ?>"
                                                                data-gradelvl-id="<?php echo $row['gradelvlID']; ?>"
                                                                data-program-id="<?php echo $row['programID']; ?>"
                                                                data-ay-id="<?php echo $row['ayID']; ?>"
                                                                data-stud-name="<?php echo $row['studname']; ?>"
                                                                data-dept-id="<?php echo $deptID; ?>"
                                                                data-faculty-id="<?php echo $row['facultyID']; ?>"
                                                                data-stud-lrn="<?php echo $row['lrn']; ?>"
                                                                >
                                                                Grade <i class="bi bi-plus-lg"></i> 
                                                            </button>
                                                            <button 
                                                                style="<?php echo ($secAyName != $activeAY) ? 'display: none;' : '' ?>"
                                                                class="btn btn-danger delete-btn btn-sm" 
                                                                data-bs-toggle="tooltip"
                                                                title="Unenroll Subject"
                                                                data-enroll-id="<?php echo $row['enrollID']; ?>"
                                                                <?php echo (
                                                                            ($row['grade'] != NULL) || 
                                                                            ($row['grade2'] != NULL) || 
                                                                            ($row['grade3'] != NULL) || 
                                                                            ($row['grade4'] != NULL)) 
                                                                        ? 'disabled' : ''?>
                                                                >
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


</body>


</html>