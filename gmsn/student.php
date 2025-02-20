<?php
include 'session.php';

require_once "includes/config.php";
require_once "fetch/fetch_activeAY.php";


$secName = $_GET['secName'] ?? '';
$progID = $_GET['programID'] ?? '';
$facultyID = $_GET['facultyID'] ?? '';
$deptID = $_GET['deptID'] ?? '';
$secID = $_GET['secID'] ?? '';
$gradelvlID = $_GET['gradelvlID'] ?? '';
$semID = $_GET['semID'] ?? '';
$ayID = $_GET['ayID'] ?? '';

try {
    $sqlSubjectName = "SELECT subjectname FROM subjects WHERE subjectID = :subjectID";
    $stmtSubName = $conn->prepare($sqlSubjectName);
    $stmtSubName->bindParam(':subjectID', $_GET['subjectID'], PDO::PARAM_INT);
    $stmtSubName->execute();

    $resultSubName = $stmtSubName->fetch(PDO::FETCH_ASSOC);

    $subjectName = $resultSubName['subjectname'] ?? '';

} catch (\Throwable $e) {
    echo 'ERROR fetching Subject Name'.$e->getMessage();
}
try {
    $sqlFacName = "SELECT CONCAT(lname, ', ', fname, ' ', mname) AS facultyName FROM faculty WHERE facultyID = :facultyID";
    $stmtFacName = $conn->prepare($sqlFacName);
    $stmtFacName->bindParam(':facultyID', $_GET['facultyID'], PDO::PARAM_INT);
    $stmtFacName->execute();

    $resultFacName = $stmtFacName->fetch(PDO::FETCH_ASSOC);

    $facultyName = $resultFacName['facultyName'] ?? '';

} catch (\Throwable $e) {
    echo 'ERROR fetching Faculty Name'.$e->getMessage();
}

try {
    $sqlSecName = "SELECT s.secName, gl.gradelvlcode, s.gradelvlID, s.programID, p.programcode
    FROM sections s
    JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
    LEFT JOIN programs p ON s.programID = p.programID
    WHERE s.secID = :secID
    AND s.gradelvlID = :gradelvlID
    OR s.programID = :programID";
    $stmtSecName = $conn->prepare($sqlSecName);
    $stmtSecName->bindParam(':secID', $_GET['secID'], PDO::PARAM_INT);
    $stmtSecName->bindParam(':gradelvlID', $_GET['gradelvlID'], PDO::PARAM_INT);
    $stmtSecName->bindParam(':programID', $_GET['programID'], PDO::PARAM_INT);
    $stmtSecName->execute();

    $resultSecName = $stmtSecName->fetch(PDO::FETCH_ASSOC);
    
    $programname = $resultSecName['programcode'] ?? '';
    $sectionname = ucwords(strtolower($resultSecName['secName'])) ?? '';
    $gradelvlname = $resultSecName['gradelvlcode'] ?? '';

    $secName = "$programname $gradelvlname - $sectionname";
} catch (\Throwable $e) {
    echo 'ERROR fetching sec name'.$e->getMessage();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Subject Enrollment</title>
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
  <script src="assets/jquery-3.7.1.min.js"></script>

  <!-- this is ajax.googleapis jquery3.5.1 -->
  <script src="assets/jquery.min.js"></script>

  <style>
    .import-excel{
        height: 30px;
    }
    .custom-container {
      margin-left: -40px;
      margin-right: -30px;
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
    .no-wrap {
        white-space: nowrap;
    }

  </style>
</head>

<body>

<?php 
require_once"support/header.php";
require_once"support/sidebar.php";
include("includes/config.php");?>
    <main id="main" class="main">
        <section class="section">
            <div class="custom-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="section_builder.php?deptID=<?php echo $deptID;?>">Sections</a></li>
                        <li class="breadcrumb-item">
                            <a href="manage_sec.php?secName=<?php echo urlencode(trim($secName))?>&programID=<?php echo $progID?>&secID=<?php echo $secID?>&gradelvlID=<?php echo $gradelvlID?>&semID=<?php echo $semID?>&ayID=<?php echo $ayID?>&deptID=<?php echo $deptID?>
                            ">
                            Manage Sections</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Students</li>
                    </ol>
                </nav>  
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <h6 class="custom-card-title fw-light">Faculty: <span class="custom-card-title span" style="font-weight: 700;"><?php echo ucwords(strtolower($facultyName)) ?></span></h6>
                                <h6 class="custom-card-title fw-light">Subject: <span class="custom-card-title span" style="font-weight: 700;"><?php echo ucwords(strtolower($subjectName)) ?></span></h6>
                                <h6 class="custom-card-title fw-light">Section: <span class="custom-card-title span" style="font-weight: 700;"><?php echo $secName ?></span></h6>
                            </div>
                            <div class="d-flex align-items-center mb-0">
                                <!-- <div class="import-excel">
                                <form action="excel/gradesheet.php" method="POST" enctype="multipart/form-data" class="me-2">
                                    <div class="input-group">
                                        <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control form-control-sm" style="display: none;" />
                                        <label for="file" class="btn btn-outline-success btn-sm">
                                            Import Excel
                                        </label>
                                        <button type="submit" name="submit" style="height: 31px" class="btn btn-success btn-sm">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                    </div>
                                </form>
                                </div> -->
                                <!-- <a href="#" class="btn btn-primary btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#addStudent">
                                    <i class="bi bi-person-add"></i> Enroll Student
                                </a> -->
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <?php if($deptID == 3):?>
                                <table class="table table-striped table-bordered datatable">
                                    <?php 
                                        if (isset($_GET['subjectID']) && isset($_GET['programID'])) {
                                            $subID = $_GET['subjectID'];
                                            $progID = $_GET['programID'];
                                            $gradelvlID = $_GET['gradelvlID'];
                                            $secID = $_GET['secID'];
                                            $facultyID = $_GET['facultyID'];
                                            $ayID = $_GET['ayID'];
                                            $semID = $_GET['semID'];
                                            $programID = $_GET['programID'];
                                        
                                            $query = "SELECT ss.*, 
                                                (SELECT lrn FROM students s WHERE ss.studID = s.studID) as lrn,
                                                (SELECT sc.ayName FROM sections sc WHERE sc.secID = ss.secID) as ayName,
                                                (SELECT semCode FROM semester WHERE semID = ss.semID) as semName,
                                                (SELECT subjectname FROM subjects WHERE subjectID = ss.subjectID) as subjects,
                                                (SELECT CONCAT(lname, ', ', fname, ' ', mname) FROM students s WHERE ss.studID = s.studID) as studname,
                                                sg.grade, sg.grade2, sg.fgrade
                                                  FROM section_students ss 
                                                  LEFT JOIN student_grades sg ON ss.studID = sg.studID AND ss.subjectID = sg.subjectID AND sg.secID = ss.secID
                                                  WHERE ss.ayID = :ayID AND ss.subjectID = :subID AND ss.secID = :secID
                                                  ORDER BY studname ASC";
                                        
                                            $stmt = $conn->prepare($query);
                                            $stmt->bindParam(':ayID', $ayID, PDO::PARAM_INT);
                                            $stmt->bindParam(':subID', $subID, PDO::PARAM_INT);
                                            $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                            $stmt->execute();
                                            $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        
                                            $count = 0;
                                        
                                        } else {
                                            echo "Not required parameters are set!";
                                        }
                                    ?>
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">LRN</th>
                                            <th>Name</th>
                                            <th class="text-center">A.Y.</th>
                                            <th class="text-center">Term</th>
                                            <th class="text-center no-wrap"><?php echo ($semID == 1) ? 'Q1' : 'Q3'; ?></th>
                                            <th class="text-center no-wrap"><?php echo ($semID == 1) ? 'Q2' : 'Q4'; ?></th>
                                            <th class="text-center no-wrap">Final Grade</th>
                                            <th style="width: 132px" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach ($curriculum as $row): 
                                                $studName = $row['studname'];
                                                $lrn = $row['lrn'];
                                                $ayName = $row['ayName'];
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo ++$count; ?>.</td> 
                                                <td class="text-center"><?php echo ($row['lrn']); ?></td>
                                                <td><?php echo ucwords(strtolower($row['studname']))?></td>
                                                <td class="text-center"><?php echo ($row['ayName']); ?></td>
                                                <td class="text-center"><?php echo ($row['semName']); ?></td>
                                                <td class="text-center" <?php if ($row['grade'] < 75) echo 'style="color: red;"'; ?>>
                                                    <?php 
                                                    if (!is_null($row['grade'])) {
                                                        echo $row['grade'];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center" <?php if ($row['grade2'] < 75) echo 'style="color: red;"'; ?>>
                                                    <?php 
                                                    if (!is_null($row['grade2'])) {
                                                        echo $row['grade2']; 
                                                    } 
                                                    ?>
                                                </td>
                                                <td class="text-center" <?php if ($row['fgrade'] < 75) echo 'style="color: red;"'; ?>>
                                                    <?php 
                                                    $grade = $row['grade'] ?? NULL;
                                                    $grade2 = $row['grade2'] ?? NULL;
                                                    $fgrade = $row['fgrade'] ?? NULL;

                                                    if (($grade != NULL) && ($grade2 != NULL)) {
                                                        if($grade < 75){
                                                            echo "<span class='text-danger fw-bold'>$fgrade</span>";
                                                        }else{
                                                            echo "<span class='fw-bold'>$fgrade</span>";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" 
                                                    class="btn btn-success grade-btn btn-sm" 
                                                    style="font-size: 11px" data-bs-toggle="modal" 
                                                    data-bs-target="#inputGradeModalSub" 
                                                    data-stud-id="<?php echo $row['studID']; ?>"
                                                    data-enroll-id="<?php echo $row['enrollID']; ?>"
                                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                                    data-subject-name="<?php echo $row['subjects']; ?>"
                                                    data-grade-first="<?php echo $row['grade']; ?>"
                                                    data-grade-second="<?php echo $row['grade2']; ?>"
                                                    data-sec-id="<?php echo $secID ?>"
                                                    data-sec-name="<?php echo $secName ?>"
                                                    data-fac-id="<?php echo $facultyID ?>"
                                                    data-fac-name="<?php echo $facultyName ?>"
                                                    data-session-id="<?php echo $ayID ?>"
                                                    data-sem-id="<?php echo $semID ?>"
                                                    data-gradelvl-id="<?php echo $gradelvlID ?>"
                                                    data-program-id="<?php echo $programID ?>">
                                                    Grade <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                    <button 
                                                    class="btn btn-danger delete-btn btn-sm" 
                                                    style="font-size: 11px; <?php echo ($ayName != $activeAY) ? 'display: none' : ''?>" 
                                                    data-stud-id="<?php echo $row['studID']; ?>" 
                                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                                    data-enroll-id="<?php echo $row['enrollID']; ?>">
                                                    <i class="bi bi-person-dash-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else:?>
                                <table class="table table-striped table-bordered datatable">
                                    <?php 
                                        if (isset($_GET['subjectID'])) {
                                            $subID = $_GET['subjectID'];
                                            $gradelvlID = $_GET['gradelvlID'];
                                            $secID = $_GET['secID'];
                                            $facultyID = $_GET['facultyID'];
                                            $ayID = $_GET['ayID'];
                                        
                                            $query = "SELECT ss.*, 
                                                (SELECT lrn FROM students s WHERE ss.studID = s.studID) as lrn,
                                                (SELECT sc.ayName FROM sections sc WHERE sc.secID = ss.secID) as ayName,
                                                (SELECT semCode FROM semester WHERE semID = ss.semID) as semName,
                                                (SELECT subjectname FROM subjects WHERE subjectID = ss.subjectID) as subjects,
                                                (SELECT CONCAT(lname, ', ', fname, ' ', mname) FROM students s WHERE ss.studID = s.studID) as studname,
                                                sg.grade, sg.grade2, sg.grade3, sg.grade4, sg.fgrade
                                                  FROM section_students ss 
                                                  LEFT JOIN student_grades sg ON ss.studID = sg.studID AND ss.subjectID = sg.subjectID AND sg.secID = ss.secID
                                                  WHERE ss.ayID = :ayID AND ss.subjectID = :subID AND ss.secID = :secID
                                                  ORDER BY studname ASC";
                                        
                                            $stmt = $conn->prepare($query);
                                            $stmt->bindParam(':ayID', $ayID, PDO::PARAM_INT);
                                            $stmt->bindParam(':subID', $subID, PDO::PARAM_INT);
                                            $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                            $stmt->execute();
                                            $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        
                                            $count = 0;
                                        
                                        } else {
                                            echo "Not required parameters are set!";
                                        }
                                    ?>
                                    <thead>
                                        <?php 
                                            $sqlSemID = "SELECT semID FROM academic_year";
                                            $semStmt = $conn->prepare($sqlSemID);
                                            $semStmt->execute();
                                            $semResult = $semStmt->fetch(PDO::FETCH_ASSOC);

                                            if($semResult){
                                                $currentSemID = $semResult['semID'];
                                            }else{
                                                $currentSemID = null;
                                            }

                                        ?>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-center">LRN</th>
                                            <th>Name</th>
                                            <th class="text-center">A.Y.</th>
                                            <th class="text-center">Q1</th>
                                            <th class="text-center">Q2</th>
                                            <th class="text-center">Q3</th>
                                            <th class="text-center">Q4</th>
                                            <th class="text-center">Final Grade</th>
                                            <th class="text-center">Remarks</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($curriculum as $row): 
                                            $studName = ucwords(strtolower($row['studname']));
                                            $lrn = $row['lrn'];
                                            $ayName = $row['ayName'];
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo ++$count; ?>.</td> 
                                                <td><?php echo $lrn; ?></td>
                                                <td><?php echo $studName ?></td>
                                                <td><?php echo ($row['ayName']); ?></td>

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



                                                <td class="text-center" style="<?php echo ($row['fgrade'] < 75) ? 'color: red;' : ''; ?>">
                                                    <?php 
                                                        $grade = $row['grade'] ?? '';
                                                        $grade2 = $row['grade2'] ?? '';
                                                        $fgrade = $row['fgrade'] ?? '';
                                                        if(($grade != NULL) && ($grade2 != NULL)){
                                                            echo $fgrade;
                                                        }
                                                    ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php
                                                        $grade = $row['grade'];
                                                        $grade2 = $row['grade2'];
                                                        $grade3 = $row['grade3'];
                                                        $grade4 = $row['grade4'];
                                                       
                                                        if(($grade != NULL) && ($grade2 != NULL) && ($grade3 != NULL) && ($grade4 != NULL)){
                                                            if($fgrade >= 75){
                                                                echo '<span class="badge badge-success">Passed</span>';
                                                            }else{
                                                                echo '<span class="badge badge-danger">Failed</span>';
                                                            }
                                                        }else{
                                                            if($row['ayName'] < $activeAY){
                                                                echo '<span class="badge badge-warning">Incomplete</span>';
                                                            }
                                                        }
                                                    ?>
                                                </td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success grade-btn btn-sm" style="font-size: 11px" 
                                                    data-bs-toggle="modal" data-bs-target="#inputGradeModalSub" 
                                                    data-stud-id="<?php echo $row['studID']; ?>"
                                                    data-enroll-id="<?php echo $row['enrollID']; ?>"
                                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                                    data-subject-name="<?php echo $row['subjects']; ?>"
                                                    data-grade-first="<?php echo ($activeSemID == 1) ? $row['grade'] : $row['grade3']; ?>"
                                                    data-grade-second="<?php echo ($activeSemID == 2) ? $row['grade2'] : $row['grade4']; ?>"
                                                    data-sec-id="<?php echo $secID ?>"
                                                    data-sec-name="<?php echo $secName ?>"
                                                    data-fac-id="<?php echo $facultyID ?>"
                                                    data-fac-name="<?php echo $facultyName ?>"
                                                    data-session-id="<?php echo $ayID ?>"
                                                    data-gradelvl-id="<?php echo $gradelvlID ?>">
                                                    Grade <i class="bi bi-plus-lg"></i> 
                                                    </button>
                                                    <button class="btn btn-danger delete-btn btn-sm" 
                                                    style="font-size: 11px; <?php echo ($ayName != $activeAY) ? 'display: none' : ''?>" 
                                                    data-stud-id="<?php echo $row['studID']; ?>" 
                                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                                    data-enroll-id="<?php echo $row['enrollID']; ?>">
                                                    <i class="bi bi-person-dash-fill"></i>
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

        <?php include("modals/studentM.php")?>                                            

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
    // Function to handle enrollment when "Enroll" button is clicked
    $('.enroll-btn').click(function() {
      // Get the student ID from the data attribute
      var studentID = $(this).data('student-id');

      $.ajax({
        url: 'enroll_student.php',
        method: 'POST',
        data: { studID: studentID }, 
        success: function(response) {
          console.log('Student enrolled successfully.');
        },
        error: function(xhr, status, error) {
          // Handle error
          console.error('Error enrolling student:', error);
        }
      });
    });

  </script>
  <script>
function handleDeleteButtonClick(enrollID, studName) {
    Swal.fire({
        title: 'Confirmation',
        text: 'You are about to unenroll ' + studName,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Remove'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_subject.php',
                method: 'POST',
                data: { enrollID: enrollID }, 
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Student Unenrolled Successfully',
                        text: 'The student ' + studName + ' has been successfully removed.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload(); 
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to unenroll student. Please try again later.'
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
            var subjectID = this.getAttribute('data-subject-id');
            var studName = this.closest('tr').querySelector('td:nth-child(3)').innerText; 
            handleDeleteButtonClick(enrollID, studName);
        });
    });
});
</script>


</body>

</html>