<?php 
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
  require_once("includes/config.php");


$facultyNum = $_SESSION['userID'];
$facultyID = $_GET['facultyID'] ?? '';
$secID = $_GET['secID'] ?? '';
$semID = $_GET['semID'] ?? '';
$programID = $_GET['programID'] ?? '';
$gradelvlID = $_GET['gradelvlID'] ?? '';
$subjectID = $_GET['subjectID'] ?? '';
$deptID = $_GET['deptID'] ?? '' ;



try {
      $sqlSubject = 
      "SELECT sg.*, sb.subjectcode, sb.subjectname
      FROM student_grades sg
      JOIN subjects sb ON sg.subjectID = sb.subjectID
      WHERE studID = :studID";

    $stmtSubject = $conn->PREPARE($sqlSubject);
    $stmtSubject->bindParam(':studID', $_GET['studID'], PDO::PARAM_INT);
    $stmtSubject->EXECUTE();
    $subjects = $stmtSubject->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $subjects = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Students' Grades</title>
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
          <?php if($deptID == 3):?>
            <li class="breadcrumb-item"><a href="advisory.php">Sections</a></li>
            <li class="breadcrumb-item"><a href="manage_students.php?secID=<?php echo $secID?>&facultyID=<?php echo $facultyID?>&semID=<?php echo $_GET['semID']?>&programID=<?php echo $programID?>&gradelvlID=<?php echo $gradelvlID?>&deptID=<?php echo $deptID?>">Student List</a></li>
            <li class="breadcrumb-item active">Student's Subjects</li>
          <?php else:?>
            <li class="breadcrumb-item"><a href="advisory.php">Sections</a></li>
            <li class="breadcrumb-item"><a href="manage_students.php?secID=<?php echo $secID?>&facultyID=<?php echo $facultyID?>&semID=<?php echo $_GET['semID']?>&deptID=<?php echo $_GET['deptID']?>">Student List</a></li>
            <li class="breadcrumb-item active">Student's Subjects</li>
          <?php endif;?>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                <table id="programTable" class="table table-bordered table-striped datatable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 20px">#</th>
                            <th>Code</th>
                            <th>Subject</th>
                            <th style="white-space: nowrap;" class="text-center">1st Qtr</th>
                            <th style="white-space: nowrap;" class="text-center">2nd Qtr</th>
                            <th style="white-space: nowrap;" class="text-center">3rd Qtr</th>
                            <th style="white-space: nowrap;" class="text-center">4th Qtr</th>
                            <th style="white-space: nowrap;" class="text-center">Final Grade</th>
                            <th class="text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                          <?php foreach ($subjects as $index => $row):?>
                          <td class="text-center"><?php echo $index + 1?>.</td>
                          <td class="text-center"><?php echo $row['subjectcode']?></td>
                          <td class="text-center"><?php echo $row['subjectname']?></td>
                          <td class="text-center"><?php echo $row['grade']?></td>
                          <td class="text-center"><?php echo $row['grade2']?></td>
                          <td class="text-center"><?php echo $row['grade3']?></td>
                          <td class="text-center"><?php echo $row['grade4']?></td>
                          <td class="text-center"><?php echo $row['fgrade']?></td>
                          <td class="text-center"><?php  ?></td>
                        </tr>
                          <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
      </div>
    </section>


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

</body>

</html>
b