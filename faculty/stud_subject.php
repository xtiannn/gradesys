<?php 
session_start();

IF (!isset($_SESSION['userID'])) {
  header('Location: ../logout.php');
  exit();
}

if (isset($_GET['excstatus'])) {
  $status = $_GET['excstatus'];
} else {
  $status = '';
}

require_once("includes/config.php");
require_once("fetch/permission.php");


$facultyNum = $_SESSION['userID'];
$studID = $_GET['studID'] ?? '';
$facultyID = $_GET['facultyID'] ?? '';
$secID = $_GET['secID'] ?? '';
$semID = $_GET['semID'] ?? '';
$programID = $_GET['programID'] ?? '';
$gradelvlID = $_GET['gradelvlID'] ?? '';
$subjectID = $_GET['subjectID'] ?? '';
$deptID = $_GET['deptID'] ?? '' ;


try {
     $fetchActiveSem = "SELECT semID, ayName FROM academic_year";
     $stmtActSem = $conn->prepare($fetchActiveSem);
     $stmtActSem->execute();

     $resultSemID = $stmtActSem->fetch(PDO::FETCH_ASSOC);
     $activeSemID = $resultSemID['semID'];
     $activeAyName = $resultSemID['ayName'];
} catch (\Throwable $e) {
 echo "Error fetching active semester: " . $e->getMessage();
}


try {
  if($deptID == 3){
      $sqlSubject = 
      "SELECT ss.*, 
      s.subjectcode AS subjectcode,
      s.subjectname AS subjectname,
      st.lrn,
      st.photo,
      CONCAT(st.lname, ', ', st.fname, ' ', st.mname) AS studname,
      sg.grade,
      sg.grade2,
      sg.fgrade,
      sg.id,
      sm.semCode,
      p.programcode,
      gl.gradelvlcode,
      sc.secName
      FROM section_students ss
      JOIN subjects s ON s.subjectID = ss.subjectID
      JOIN students st ON st.studID = ss.studID
      LEFT JOIN student_grades sg ON sg.studID = ss.studID AND sg.subjectID = ss.subjectID AND sg.secID = ss.secID
      JOIN semester sm ON ss.semID = sm.semID
      JOIN programs p ON ss.programID = p.programID
      JOIN grade_level gl ON ss.gradelvlID = gl.gradelvlID
      JOIN sections sc ON ss.secID = sc.secID
      WHERE ss.studID = :studID
      AND ss.semID = :semID
      AND ss.secID = :secID
      AND ss.programID = :programID
      AND ss.gradelvlID = :gradelvlID
      AND ss.subjectID != :subjectID
      ORDER BY s.subjectname ASC";
    $stmtSubject = $conn->PREPARE($sqlSubject);
    $stmtSubject->bindParam(':studID', $studID, PDO::PARAM_INT);
    $stmtSubject->bindParam(':semID', $semID, PDO::PARAM_INT);
    $stmtSubject->bindParam(':secID', $secID, PDO::PARAM_INT);
    $stmtSubject->bindParam(':programID', $programID, PDO::PARAM_INT);
    $stmtSubject->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
    $stmtSubject->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
    $stmtSubject->EXECUTE();
    $subjects = $stmtSubject->fetchAll(PDO::FETCH_ASSOC);
  }else{
        $sqlSubject = 
        "SELECT ss.*, 
        s.subjectcode AS subjectcode,
        s.subjectname AS subjectname,
        st.lrn,
        st.photo,
        CONCAT(st.lname, ', ', st.fname, ' ', st.mname) AS studname,
        sg.grade,
        sg.grade2,
        sg.grade3,
        sg.grade4,
        sg.fgrade,
        sg.id,
        gl.gradelvlcode,
        sc.secName
        FROM section_students ss
        LEFT JOIN subjects s ON s.subjectID = ss.subjectID
        LEFT JOIN students st ON st.studID = ss.studID
        LEFT JOIN student_grades sg ON sg.studID = ss.studID AND sg.subjectID = ss.subjectID AND sg.secID = ss.secID
        JOIN grade_level gl ON ss.gradelvlID = gl.gradelvlID
        JOIN sections sc ON ss.secID = sc.secID
        WHERE ss.studID = :studID
        AND ss.secID = :secID
        AND ss.gradelvlID = :gradelvlID
        AND ss.subjectID != :subjectID
        ORDER BY s.subjectname ASC";
    $stmtSubject = $conn->PREPARE($sqlSubject);
    $stmtSubject->bindParam(':studID', $studID, PDO::PARAM_INT);
    $stmtSubject->bindParam(':secID', $secID, PDO::PARAM_INT);
    $stmtSubject->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
    $stmtSubject->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
    $stmtSubject->EXECUTE();
    $subjects = $stmtSubject->fetchAll(PDO::FETCH_ASSOC);
  }

    if (!empty($subjects)) {
      $studentName = ucwords(strtolower($subjects[0]['studname'])); 
      $sectionName = ucwords(strtolower($subjects[0]['secName'])); 
      $gradeLvlCode = $subjects[0]['gradelvlcode']; 
      $programCode = $subjects[0]['programcode'] ?? ''; 
      $photo = $subjects[0]['photo']; 

      $ConcatedSecName = "$gradeLvlCode - $sectionName";
    } else {
        $studentName = '';
        $sectionName = 'No sectionName found';
        $gradeLvlCode = 'No gradeLvlCode found';
        $programCode = 'No programCode found';
        $photo = 'No photo found';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $subjects = [];
}



try {
    // Fetch the active academic year and semester
    $fetchActiveAY = "SELECT ay.ayName, s.semID 
                      FROM academic_year ay
                      JOIN semester s ON ay.semID = s.semID";

    $stmtActiveAY = $conn->prepare($fetchActiveAY);
    $stmtActiveAY->execute();
    $result = $stmtActiveAY->fetch(PDO::FETCH_ASSOC);

    $activeAy = $result['ayName'] ?? NULL;
    $activeSem = $result['semID'] ?? NULL;

    // Fetch the section academic year and semester
    try {
      $fetchSecAY = "SELECT ayName, semID
                    FROM sections 
                    WHERE secID = :secID";
  
      $stmtSecAY = $conn->prepare($fetchSecAY);
      $stmtSecAY->bindParam(':secID', $secID, PDO::PARAM_INT);
      $stmtSecAY->execute();
      
      $resultSecAy = $stmtSecAY->fetch(PDO::FETCH_ASSOC);
  
      $secAy = $resultSecAy['ayName'] ?? null;
      $secAyName = $resultSecAy['ayName'] ?? null;
      $secSem = $resultSecAy['semID'] ?? null;
  } catch (\Throwable $e) {
      echo "Error fetching Section's AY: " . $e->getMessage();
  }                          

} catch (\Throwable $e) {
    echo 'Error fetching Active AY: ' . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Advisory Class</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../gmsn/assets/img/gmsnlogo.png" rel="icon">
  <link href="../gmsn/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../gmsn/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
    th{
      font-size: 15px;
    }
    td{
        font-size: 14px;
    }
    .custom-container {
      margin-left: -10px;
      margin-right: -15px;
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
    .txtgrade {
      width: 80px;
      padding: 5px;
      border: 2px solid #ccc;
      border-radius: 4px;
      font-size: 13px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .txtgrade:focus {
      border-color: #ADD8E6; 
      outline: none;
      box-shadow: 0 0 8px rgba(173, 216, 230, 0.6);
    }
    .editable-cell {
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
      min-width: 80px;
    }
  </style>
</head>

<body>

  <?php require_once "support/header.php"?>
  <?php require_once "support/sidebar.php"?>

  <main id="main" class="main">
    <div class="pagetitle">
      <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="advisory.php">Sections</a></li>
          <li class="breadcrumb-item"><a href="manage_students.php?secID=<?php echo $secID?>&facultyID=<?php echo $facultyID?>&semID=<?php echo $semID?>&programID=<?php echo $programID?>&gradelvlID=<?php echo $gradelvlID?>&deptID=<?php echo $deptID?>">Student List</a></li>
          <li class="breadcrumb-item active">Student's Subjects</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header" style="display: <?php echo ($studentName === '') ? 'none' : 'block'?>">
              <div class="d-flex align-items-start">
                <div class="student-photo-container me-3">
                  <img src="<?php echo !empty($photo) ? '../gmsn/uploads/photo/' . $photo : '../gmsn/assets/img/user.png'; ?>" alt="Student Photo" class="img-fluid" style="width: 80px; height: 80px; object-fit: cover;">
                </div>
                <div class="mt-1">
                  <div class="d-flex">
                    <h6 class="custom-card-title fw-normal" style="font-size: 15px; margin-bottom: 0; width: 80px;">Name:</h6>
                    <span class="custom-card-title" style="font-weight: 700; font-size: 15px;"><?php echo $studentName; ?></span>
                  </div>
                  <div class="programDiv" style="display: <?php echo ($deptID==3) ? 'block' : 'none'?>">
                    <div class="d-flex">
                      <h6 class="custom-card-title fw-normal" style="font-size: 15px; margin-bottom: 0; width: 80px;">Program:</h6>
                      <span class="custom-card-title" style="font-weight: 700; font-size: 15px;"><?php echo $programCode; ?></span>
                    </div>
                  </div>
                  <div class="d-flex">
                    <h6 class="custom-card-title fw-normal" style="font-size: 15px; margin-bottom: 0; width: 80px;">Section:</h6>
                    <span class="custom-card-title" style="font-weight: 700; font-size: 15px;"><?php echo $ConcatedSecName; ?></span>
                  </div>
                </div>
                
                <!-- Buttons aligned to the end of the card-header -->
                <div class="ms-auto d-flex align-items-center mt-4">
                    <?php 
                      if($deptID == 3){
                        $display = ($activeAy === $secAy && $activeSem === $secSem) ? 'block' : 'none';
                      } else {
                        $display = ($activeAy === $secAy) ? 'block' : 'none';
                      }
                    ?>

                    <!-- Import Excel Form -->
                    <div class="import-excel mt-2" style="display: <?php echo $display?>">
                        <form action="../gmsn/excel/<?php echo ($deptID == 3) ? 'test2.php' : 'test3.php'?>" method="POST" enctype="multipart/form-data" class="me-2">
                            <div class="input-group">
                                <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control form-control-sm" style="display: none;" />
        
                                <label for="file" class="btn btn-outline-success btn-sm">
                                    Import Grades
                                </label>
                                <!-- Submit button -->
                                <button type="submit" name="submit" style="height: 31px" class="btn btn-success btn-sm">
                                    <i class="bi bi-upload"></i>
                                </button>
                            </div>
                            <!-- File name preview -->
                            <div id="fileNamePreview" style="font-size: 12px; color: #555; visibility: hidden; height: 20px; line-height: 20px;"></div>
                            <input type="hidden" name="facultyID" value="<?php echo $facultyID?>">
                            <input type="hidden" name="secID" value="<?php echo $secID?>">
                            <input type="hidden" name="semID" value="<?php echo $semID?>" >
                            <input type="hidden" name="deptID" value="<?php echo $deptID?>">
                            <input type="hidden" name="studID" value="<?php echo $studID?>">
                            <input type="hidden" name="activeAY" value="<?php echo $activeAy?>">
                        </form>
                    </div>
                  </div>

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
                            <td class="text-center"  
                                style="<?php echo ($firstSwitchValue == 1 && $row['ayName'] == $activeAy) ? 'border: 2px solid black; border-radius: 8px' : ''?>"
                                contenteditable="<?php echo ($firstSwitchValue == 1 && $row['ayName'] == $activeAy) ? 'true' : 'false'?>" data-field="grade" data-id="<?php echo $row['enrollID']; ?>">
                                <?php echo ($row['grade'] != 0) ? $row['grade'] : ''; ?>
                            </td>
                            <td class="text-center" style="<?php echo ($secondSwitchValue == 1 && $row['ayName'] == $activeAy) ? 'border: 2px solid black; border-radius: 8px' : ''?>"
                                contenteditable="<?php echo ($secondSwitchValue == 1 && $row['ayName'] == $activeAy) ? 'true' : 'false'?>" data-field="grade2" data-id="<?php echo $row['enrollID']; ?>">
                                <?php echo ($row['grade2'] != 0) ? $row['grade2'] : ''; ?>
                            </td>
                            <td class="text-center fw-bold" <?php if ($row['fgrade'] < 75) echo 'style="color: red;"'; ?>>
                            <?php 
                                $grade = $row['grade'];
                                $grade2 = $row['grade2'];

                                if ($grade != 0 && $grade2 != 0) {
                                    echo $row['fgrade'];
                                }
                                elseif ((empty($grade) && !empty($grade2)) || ($row['ayName'] != $activeAy)){
                                    echo '<span class="text-warning">INC</span>';
                                }
                                else 
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
                            <td class="text-center" style="white-space: nowrap;"><?php echo $row['ayName']; ?></td>
                            <td><?php echo $row['code']; ?></td>
                            <td><?php echo $row['subject']; ?></td>
                            <td class="text-center"  style="<?php echo (($activeSem == 1 && $firstSwitchValue == 1) && ($row['ayName'] == $activeAy)) ? 'border: 2px solid black; border-radius: 8px' : ''?>"
                                contenteditable="<?php echo (($activeSem == 1 && $firstSwitchValue == 1) && ($row['ayName'] == $activeAy)) ? 'true' : 'false'?>" data-field="grade" data-id="<?php echo $row['enrollID']; ?>">
                                <?php echo ($row['grade'] != 0) ? $row['grade'] : ''; ?>
                            </td>
                            <td class="text-center" style="<?php echo (($activeSem == 1 && $secondSwitchValue == 1) && ($row['ayName'] == $activeAy))? 'border: 2px solid black; border-radius: 8px' : ''?>"
                                contenteditable="<?php echo (($activeSem == 1 && $secondSwitchValue == 1) && ($row['ayName'] == $activeAy)) ? 'true' : 'false'?>" data-field="grade2" data-id="<?php echo $row['enrollID']; ?>">
                                <?php echo ($row['grade2'] != 0) ? $row['grade2'] : ''; ?>
                            </td>
                            <td class="text-center" style="<?php echo (($activeSem == 2 && $firstSwitchValue == 1) && ($row['ayName'] == $activeAy)) ? 'border: 2px solid black; border-radius: 8px' : ''?>"
                                contenteditable="<?php echo (($activeSem == 2 && $firstSwitchValue == 1) && ($row['ayName'] == $activeAy)) ? 'true' : 'false'?>" data-field="grade3" data-id="<?php echo $row['enrollID']; ?>">
                                <?php echo ($row['grade3'] != 0) ? $row['grade3'] : ''; ?>
                            </td>
                            <td class="text-center" style="<?php echo (($activeSem == 2 && $secondSwitchValue == 1) && ($row['ayName'] == $activeAy)) ? 'border: 2px solid black; border-radius: 8px' : ''?>"
                                contenteditable="<?php echo (($activeSem == 2 && $secondSwitchValue == 1) && ($row['ayName'] == $activeAy)) ? 'true' : 'false'?>" data-field="grade4" data-id="<?php echo $row['enrollID']; ?>">
                                <?php echo ($row['grade4'] != 0) ? $row['grade4'] : ''; ?>
                            </td>
                            <td class="text-center fw-bold" <?php if ($row['fgrade'] < 75) echo 'style="color: red;"'; ?>>
                            <?php 
                                $grade = $row['grade'];
                                $grade2 = $row['grade2'];
                                $grade3 = $row['grade3'];
                                $grade4 = $row['grade4'];

                                if ($grade != 0 && $grade2 != 0) {
                                    echo $row['fgrade'];
                                }
                                elseif (((empty($grade) || empty($grade2) || empty($grade3)) && !empty($grade4)) || ($row['ayName'] != $activeAy)){
                                    echo '<span class="text-warning">INC</span>';
                                }
                                else 
                            ?>

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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif?>
            </div>
        </div>
      </div>
      </div>
    </section>

    <?php include"../gmsn/modals/studentM.php"?>

  </main><!-- End #main -->
    
  <?php require_once "support/footer.php"?>            

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="assets/sweetalert2.all.min.js"></script>

  <!-- Vendor JS Files -->
  <script src="../gmsn/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../gmsn/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../gmsn/assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
  <script src="../gmsn/assets/jquery-3.7.1.min.js"></script>
  <script src="../gmsn/assets/js/main.js"></script>
  <!-- this is ajax.googleapis jquery3.5.1 -->
  <script src="../gmsn/assets/jquery.min.js"></script>

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
                    url: '../gmsn/save_grade_ajax.php',
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
