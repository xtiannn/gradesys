<?php 
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
  require_once("includes/config.php");


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sections</title>
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
</style>
<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>
  <?php 
    require_once("includes/config.php");
    $query = 
    "SELECT ss.*, (SELECT lrn FROM students s WHERE ss.studID = s.studID) as lrn,
    (SELECT CONCAT(lname, ', ', fname, ' ', mname) FROM students s WHERE ss.studID = s.studID) as studname,
    (SELECT programcode FROM programs p WHERE ss.programID = p.programID) as program
    FROM section_students ss";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $count = 0;

    ?>
    <main id="main" class="main">
        <section class="section">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Students</li>
                    </ol>
                </nav>
                        <div class="row">
                            <div class="col-10">
                                <h5 class="card-title"><?php 
                                $secName = isset($_GET['secName']) ? $_GET['secName'] : '';
                                echo $secName ?></h5>
                            </div>
                            <!-- <div class="col-md-2">
                                <a href="#" class="btn btn-primary btn-sm">
                                <i class="bi bi-printer"></i> Print
                                </a>
                            </div> -->
                        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>LRN</th>
                                        <th>Name</th>
                                        <th>Program</th>
                                        <th class="text-center" style="width: 250px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($curriculum as $row): 
                                         if (!in_array($row['studID'], $curriculum)):
                                            // If not printed, add it to the printedStudIDs array
                                            $curriculum[] = $row['studID'];
                                    ?>
                                        <tr>
                                            <td><?php echo ++$count; ?></td> 
                                            <td><?php echo ($row['lrn']); ?></td>
                                            <td><?php echo ($row['studname']); ?></td>
                                            <td><?php echo ($row['program']); ?></td>
                                            <td class="text-center">
                                                <!-- <button class="btn btn-info btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#enrollSubjects">
                                                    <i class="bi bi-gear"></i> Subjects
                                                </button> -->
                                                <a class="btn btn-primary btn-sm" type="button" href="students_subjRecord.php?studID=<?php echo $row['studID']; ?>&studName=<?php echo urlencode($row['studname']); ?>&studLRN=<?php echo urlencode($row['lrn']); ?>">
                                                    <i class="bi bi-gear"></i> Grades
                                                </a>
                                                <button class="btn btn-danger delete-btn btn-sm"  data-enroll-id="<?php echo $row['enrollID']; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php 
                                    endif;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
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

</body>


</html>