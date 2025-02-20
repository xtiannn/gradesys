<?php 
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
  require_once("includes/config.php");
  require_once("fetch/permission.php");





  if (isset($_GET['facultyID']) && $_GET['subjectID']){
    $facultyID = $_GET['facultyID'];
    $subjectID = $_GET['subjectID'];
    $faID = $_GET['faID'] ?? NULL;
    $semID = $_GET['semID'] ?? NULL;
    $secID = $_GET['secID'];
    $deptID = $_GET['deptID'] ?? '';
    $programID = $_GET['deptID'] ?? NULL;

    try {
      $fetchSecName = "SELECT ss.secName, ss.gradelvlID, gl.gradelvlcode, ss.programID, p.programcode  
                        FROM sections ss 
                        LEFT JOIN programs p ON ss.programID = p.programID
                        JOIN grade_level gl ON ss.gradelvlID = gl.gradelvlID
                        WHERE ss.secID = :secID";
      $stmtSecName = $conn->prepare($fetchSecName);
      $stmtSecName->bindParam(':secID', $secID, PDO::PARAM_INT);
      $stmtSecName->execute();
      $secresult = $stmtSecName->fetch(PDO::FETCH_ASSOC);

      $secname = $secresult['secName'] ?? NULL;
      $programcode = $secresult['programcode'] ?? NULL;
      $gradelvl = $secresult['gradelvlcode'] ?? NULL;

      $secName = "$programcode $gradelvl - $secname";

    } catch (\Throwable $e) {
      echo "ERROR: " . $e->getMessage();
    }

    try {
      $fetchAyName = "SELECT ayName FROM facultyAssign WHERE facultyAssignID = :faID";
      $stmtAyName = $conn->prepare($fetchAyName);
      $stmtAyName->bindParam(':faID', $faID, PDO::PARAM_INT);
      $stmtAyName->execute();
      $result = $stmtAyName->fetch(PDO::FETCH_ASSOC);

      $ayName = $result['ayName'];

    } catch (\Throwable $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }
      
      $sqlStudents = 
      "SELECT s.studID, CONCAT(s.lname, ', ', s.fname, ' ', s.mname) AS studName, sg.remarks, sg.ayName,
      CONCAT(f.lname, ', ', f.fname, ' ', f.mname) AS facultyName,
      s.lrn, sg.grade, sg.grade2, sg.grade3, sg.grade4, sg.fgrade, sm.semCode, sm.semID, sb.subjectname, sc.secName, sc.secID, 
      p.programcode, p.programID,
      gl.gradelvlcode, gl.gradelvlID, ss.enrollID, ss.subjectID, ss.ayID, ss.semID
      FROM section_students ss
      JOIN students s ON ss.studID = s.studID
      LEFT JOIN semester sm ON ss.semID = sm.semID
      JOIN subjects sb ON ss.subjectID = sb.subjectID
      LEFT JOIN faculty f ON ss.facultyID = f.facultyID
      JOIN sections sc ON ss.secID = sc.secID
      LEFT JOIN programs p ON ss.programID = p.programID
      JOIN grade_level gl ON ss.gradelvlID = gl.gradelvlID
      LEFT JOIN student_grades sg ON ss.studID = sg.studID AND ss.subjectID = sg.subjectID AND sc.secID = sg.secID
      WHERE ss.subjectID = :subjectID AND ss.secID = :secID
      ORDER BY studName ASC";

      try {
        $stmtStudents = $conn->prepare($sqlStudents);
        $stmtStudents->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
        $stmtStudents->bindParam(':secID', $secID, PDO::PARAM_INT);
        $stmtStudents->execute();
        $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

        $count = 1;
      } catch (\Throwable $e) {
        echo '<span>Error: ' . $e->getMessage() . '</span>';  
      }


  try {
    // Fetch the active academic year and semester
    $fetchActiveAy = "SELECT ay.ayName, s.semID FROM academic_year ay
                      JOIN semester s ON ay.semID = s.semID";
                      
    $stmtActiveAY = $conn->prepare($fetchActiveAy);
    $stmtActiveAY->execute();
    $result = $stmtActiveAY->fetch(PDO::FETCH_ASSOC);

    $activeAy = $result['ayName'] ?? '';
    $activeSem = $result['semID'] ?? '';

    try {
      $fetchSecAY = "SELECT s.ayName, s.semID
      FROM sections s
      LEFT JOIN semester sm ON s.semID = sm.semID
      WHERE secID = :secID";

      $stmtSecAY = $conn->prepare($fetchSecAY);
      $stmtSecAY->bindParam(':secID', $secID, PDO::PARAM_INT);
      $stmtSecAY->execute();
      
      $resultSecAy = $stmtSecAY->fetch(PDO::FETCH_ASSOC);
  
      $secAy = $resultSecAy['ayName'] ?? 'error fetching';
      $secSem = $resultSecAy['semID'] ?? null;
        
    } catch (\Throwable $e) {
        echo "Error Fetching section's ay and sem: " . $e->getMessage();
    }

} catch (\Throwable $e) {
    echo 'Error Fetching the ay and sem: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Students</title>
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
  <link href="../gmsn/assets/css/style.css" rel="stylesheet">


  <style>


    .import-excel{
        height: 30px;
    }
    .customer-container {
      margin-left: -60px;
      margin-right: -15px;
    }
    .table {
      font-size: 0.90rem; 
    }

    .table th, .table td {
      padding: 0.5rem; 
    }

    .table td {
      white-space: nowrap; 
    }

    .table-responsive {
      overflow-x: auto;
    }

    .table th, .table td {
      vertical-align: middle;
    }

    .btn-sm {
      font-size: 0.75rem;
    }

    .text-center {
      text-align: center;
    }

    @media (max-width: 768px) {
      .table-responsive {
        overflow-x: auto;
      }
    }
    .custom-card-title {
    padding: 1px 0;
    margin: 2px 2px;
    font-size: 15px;
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
  <?php require_once"support/sidebar.php";?>

  <main id="main" class="main">
    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="assignedSub.php">Assigned Subjects</a></li>
          <li class="breadcrumb-item active">Students</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column">
                  <?php
                        $semID = $_GET['semID'] ?? NULL;

                    $sqlfac = "SELECT CONCAT(lname, ', ', fname, ' ', mname) AS facultyName FROM faculty WHERE facultyID = :facultyID";
                    $stmtfac = $conn->prepare($sqlfac);
                    $stmtfac->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);

                    $sqlsubject = "SELECT subjectname FROM subjects WHERE subjectID = :subjectID";
                    $stmlsubject = $conn->prepare($sqlsubject);
                    $stmlsubject->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);

                    try {
                        $stmtfac->execute();
                        $facultyData = $stmtfac->fetch(PDO::FETCH_ASSOC);
                        $facultyName = $facultyData ? $facultyData['facultyName'] : '';

                        $stmlsubject->execute();
                        $fsubjectData = $stmlsubject->fetch(PDO::FETCH_ASSOC);
                        $subjectName = $fsubjectData ? $fsubjectData['subjectname'] : '';

                    } catch (PDOException $e) {
                        // Handle SQL error
                        echo '<span>Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
                    }
                    ?>
                    <h6 class="custom-card-title fw-normal">Faculty: <span class="custom-card-title span fw-bold"><?php echo ucwords(trim($facultyName)) ?></span></h6>
                    <h6 class="custom-card-title fw-normal">Subject: <span class="custom-card-title span fw-bold"><?php echo ucwords(trim($subjectName)) ?></span></h6>
                    <h6 class="custom-card-title fw-normal">Section: <span class="custom-card-title span fw-bold"><?php echo isset($secName) ? ucwords(trim($secName)) : ''; ?></span></h6>
                    </div>
                <div class="d-flex align-items-center mb-0">
                  <!-- Download Excel Button -->
                  <?php 
                    if($semID != NULL){
                      if($activeAy === $secAy && $activeSem === $secSem){
                        $display = 'block;';
                      }else{
                        $display = 'none;';
                      }
                    }else{
                      if($activeAy === $secAy){
                        $display = 'block;';
                      }else{
                        $display = 'none;';
                      }
                    }
                  ?>
                  <!-- <button style="display: <?php //echo $display?>"
                      onclick="window.location.href= 'excel/gradesheet_download.php?subjectID=<?php //echo $subjectID?>&secID=<?php echo $secID?>&semID=<?php echo $semID?>&facultyID=<?php echo $facultyID?>&deptID=<?php echo $deptID?>&activeSem=<?php echo $activeSem?>'" class="btn btn-success btn-sm me-2">
                      <i class="bi bi-download me-1"></i> Template
                  </button> -->
                  <div class="import-excel" style="display: <?php echo $display?>">
                        <!-- excel/gradesheetSub.php -->
                    <form action="../gmsn/excel/<?php echo ($deptID == 3) ? 'test.php' : 'testJHS.php'?>" method="POST" enctype="multipart/form-data" class="me-2">
                          <div class="input-group">
                              <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control form-control-sm" style="display: none;" />
                              <label for="file" class="btn btn-outline-success btn-sm">
                                  Import Grades
                              </label>
                              <button type="submit" name="submit" style="height: 31px" class="btn btn-success btn-sm">
                                  <i class="bi bi-upload"></i>
                              </button>
                          </div>
                          <div id="fileNamePreview" style="font-size: 12px; color: #555;"></div>
                          <input type="hidden" name="facultyID" value="<?php echo $facultyID?>">
                          <input type="hidden" name="secID" value="<?php echo $secID?>">
                          <input type="hidden" name="semID" value="<?php echo $activeSem?>">
                          <input type="hidden" name="deptID" value="<?php echo $deptID?>">
                          <input type="hidden" name="subjectID" value="<?php echo $subjectID?>">
                          <input type="hidden" name="activeAY" value="<?php echo $activeAy?>">
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
                            } else {
                                fileNamePreview.textContent = ""; 
                            }
                        });
                    </script>
                  </div>
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
                            <th class="text-center">LRN</th>
                            <th>Name</th>
                            <th class="text-center"><?php echo ($_GET['semID'] == 1) ? 'Q1' : 'Q3' ?></th>
                            <th class="text-center"><?php echo ($_GET['semID'] == 1) ? 'Q2' : 'Q4' ?></th>
                            <th class="text-center" style="white-space: no-wrap">Final Grade</th>
                            <th class="text-center" style="width: 150px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php
        
                            foreach ($students as $row) :

                
                            ?>
                            <tr>
                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                <td class="text-center" style="white-space: nowrap;"><?php echo $row['ayName']; ?></td>
                                <td class="text-center"><?php echo $row['semCode']; ?></td>
                                <td><?php echo $row['lrn']; ?></td>
                                <td><?php echo $row['studName']; ?></td>
                                <td class="text-center" 
                                    style="<?php echo ($firstSwitchValue == 1 && $row['ayName'] == $activeAy) ? 'border: 2px solid black; border-radius: 8px' : ''?>" 
                                    contenteditable="<?php echo ($firstSwitchValue == 1 && $row['ayName'] == $activeAy) ? 'true' : 'false'?>" 
                                    data-field="grade" data-id="<?php echo $row['enrollID']; ?>">
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
                                    data-ay-name="<?php echo $secAy; ?>"
                                    data-sec-id="<?php echo $secID; ?>"
                                    data-enroll-id="<?php echo $row['enrollID']; ?>"
                                    data-stud-id="<?php echo $row['studID']; ?>"
                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                    data-sem-id="<?php echo $row['semID']; ?>"
                                    data-gradelvl-id="<?php echo $row['gradelvlID']; ?>" 
                                    data-faculty-id="<?php echo $facultyID; ?>"   
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
                            <th class="text-center">LRN</th>
                            <th>Name</th>
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
        
                            foreach ($students as $row) :

                
                            ?>
                            <tr>
                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                <td class="text-center" style="white-space: nowrap;"><?php echo $secAy; ?></td>
                                <td><?php echo $row['lrn']; ?></td>
                                <td><?php echo $row['studName']; ?></td>
                                <td class="text-center" 
                                    style="<?php echo (($activeSem == 1 && $firstSwitchValue == 1) && ($row['ayName'] == $activeAy))  ? 'border: 2px solid black; border-radius: 8px' : ''?>"
                                    contenteditable="<?php echo (($activeSem == 1 && $firstSwitchValue == 1) && ($row['ayName'] == $activeAy)) ? 'true' : 'false'?>" data-field="grade" data-id="<?php echo $row['enrollID']; ?>">
                                    <?php echo ($row['grade'] != 0) ? $row['grade'] : ''; ?>
                                </td>
                                <td class="text-center" style="<?php echo (($activeSem == 1 && $secondSwitchValue == 1) && ($row['ayName'] == $activeAy)) ? 'border: 2px solid black; border-radius: 8px' : ''?>"
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
                                <button type="button" 
                                    class="btn btn-success save-btn btn-sm" 
                                    data-ay-name="<?php echo $secAy; ?>"
                                    data-sec-id="<?php echo $secID; ?>"
                                    data-enroll-id="<?php echo $row['enrollID']; ?>"
                                    data-stud-id="<?php echo $row['studID']; ?>"
                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                    data-gradelvl-id="<?php echo $row['gradelvlID']; ?>" 
                                    data-faculty-id="<?php echo $facultyID; ?>"   
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
    <?php include "../gmsn/modals/studentM.php"?>
  </main><!-- End #main -->
  <?php require_once"support/footer.php"?>            

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Template Main JS File -->
    <script src="../gmsn/assets/jquery-3.7.1.min.js"></script>
    <script src="../gmsn/assets/js/main.js"></script>
    <!-- this is ajax.googleapis jquery3.5.1 -->





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
                            timer: 2000
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


  


<!-- jQuery (Ensure only one version is included) -->
<script src="../gmsn/assets/jquery-3.7.1.min.js"></script>

<!-- Vendor JS Files -->
<script src="../gmsn/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../gmsn/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="../gmsn/assets/vendor/tinymce/tinymce.min.js"></script>

<!-- SweetAlert2 -->
<script src="../gmsn/assets/sweetalert2.all.min.js"></script>

<!-- Template Main JS File -->
<script src="../gmsn/assets/js/main.js"></script>

<?php if (isset($_GET['notEnrolled'])): ?>
  <script>
      const notEnrolled = <?php echo json_encode($_GET['notEnrolled']); ?>;

      if (notEnrolled.length > 0) {
          Swal.fire({
              title: 'Notice',
              text: `Some students were not enrolled: ${notEnrolled.join(', ')}`,
              icon: 'warning',
              confirmButtonText: 'Okay'
          });
      }
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
    handleAlert('studNotMatched', 'Student ID Not Matched!', 'The Excel file does not match the Student ID', 'warning');
    handleAlert('noFile', 'No File Uploaded!', 'Please upload a valid Excel file.', 'error');
    handleAlert('facNotMatched', 'Faculty Not Matched!', 'The Excel file does not match the section adviser.', 'warning');
    handleAlert('secNotMatched', 'Section Not Matched!', 'The Excel file does not match the selected section.', 'warning');
    handleAlert('lvlNotMatched', 'Grade Level Not Matched!', 'The Excel file does not match the selected grade level.', 'warning');
    handleAlert('progNotMatched', 'Program Not Matched!', 'The Excel file does not match the selected program.', 'warning');
    handleAlert('semNotMatched', 'Semester Not Matched!', 'The Excel file does not match the selected semester.', 'warning');
    handleAlert('subjNotMatched', 'Subject Not Matched!', 'The Excel file does not match the selected subject.', 'warning');
</script>


<script>
$(document).ready(function(){
    $('.grade-btn').click(function(){
        var enrollID = $(this).data('enroll-id');
        var studID = $(this).data('stud-id');
        var subjectID = $(this).data('subject-id');
        var subjectName = $(this).data('subject-name');
        var first = $(this).data('grade-first');
        var second = $(this).data('grade-second');
        var secID = $(this).data('sec-id');
        var secName = $(this).data('sec-name');
        var facultyID = $(this).data('fac-id');
        var facultyName = $(this).data('fac-name');
        var ayID = $(this).data('session-id');
        var semID = $(this).data('sem-id');
        var gradelvlID = $(this).data('gradelvl-id');
        var programID = $(this).data('program-id');

        $('#enrollIDSub').val(enrollID);
        $('#studIDSub').val(studID);
        $('#subjectIDSub').val(subjectID);
        $('#subjectNameSecSub').val(subjectName);
        $('#semIDSub').val(semID);
        $('#gradelvlIDSub').val(gradelvlID);
        $('#programIDSub').val(programID);
        $('#subjectNameLegendSub').text(subjectName);
        if (first !== 0) {
            $('#txtgradeSubSub').val(first);
        }

        if (second !== 0) {
            $('#txtgradeSubSub2').val(second);
        }
        $('#secIDSub').val(secID);
        $('#secNameSub').val(secName);
        $('#facultyIDSub').val(facultyID);
        $('#facultyNameSub').val(facultyName);
        $('#ayIDSub').val(ayID);
    });
});

</script>  
</body>


</html>