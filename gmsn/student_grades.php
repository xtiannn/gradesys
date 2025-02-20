<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Grade Report</title>
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
</head>

<body>

  <?php 
  require_once"support/header.php";
  require_once"support/sidebar.php";
  include("includes/config.php");
?>
    <main id="main" class="main">
        <section class="section">
            <div class="container">
            <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                      <li class="breadcrumb-item"><a href="section_builder.php">Sections</a></li>
                      <li class="breadcrumb-item"><a href="manage_sec.php">Manage Sections</a></li>
                      <li class="breadcrumb-item active" aria-current="page">Students</li>
                    </ol>
                </nav>
                        <div class="row">
                            <div class="col-md-10">
                            <h5 class="card-title">
                                <?php 
                                if (isset($_GET['studID'], $_GET['semID'])) {
                                    $studID = $_GET['studID'];
                                    $semID = $_GET['semID'];
                                    $studName = $_GET['studName'];
                                    $grade = $_GET['grade'];
                                    
                                }echo  $studName
                                ?>
                            </h5>
                            </div>
                            <div class="col-md-2">
                            <button id="printButton" class="btn btn-primary btn-sm">
                            <i class="bi bi-printer"></i> Print
                            </button>
                            </div>
                        </div> 
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <table class="table datatable">
                                <?php
                                require_once("includes/config.php");
                                    $query = "SELECT es.*, s.subjectname, sm.semName
                                    FROM enrolled_student es 
                                    JOIN subjects s ON es.subjectID = s.subjectID
                                    JOIN semester sm ON es.semID = sm.semID
                                    WHERE es.semID = :semID AND studID = :studID AND grade = :grade";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                                    $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                                    $stmt->bindParam(':grade', $grade, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $count = 0;
                                ?>
                                    <?php if ($row['semID'] == 1): ?>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Subject</th>
                                                <th>Term</th>
                                                <th class="text-center">1st Qtr</th>
                                                <th class="text-center">2nd Qtr</th>
                                                <th class="text-center">Final Grade</th>
                                                <th class="text-center">Remarks</th>
                                            </tr>
                                        </thead>
                                    <?php else: ?>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Subject</th>
                                                <th class="text-center">Term</th>
                                                <th class="text-center">3rd Qtr</th>
                                                <th class="text-center">4th Qtr</th>
                                                <th class="text-center">Final Grade</th>
                                                <th class="text-center">Remarks</th>
                                            </tr>
                                        </thead>
                                    <?php endif; ?>
                                    <tbody>
                                        <?php foreach ($programs as $row): ?>
                                                <td><?php echo ++$count; ?></td> 
                                                <td><?php echo htmlspecialchars($row['subjectname']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($row['semName']); ?></td>
                                                <td class="text-center" <?php echo ($row['grade'] >= 75) ? 'style="color: black;"' : 'style="color: red;"'; ?>><?php echo htmlspecialchars($row['grade']); ?></td>
                                                <td class="text-center" <?php echo ($row['grade2'] >= 75) ? 'style="color: black;"' : 'style="color: red;"'; ?>><?php echo htmlspecialchars($row['grade2']); ?></td>
                                                <?php $finalAverage = ($row['grade'] + $row['grade2']) / 2; ?>
                                                <td class="text-center">
                                                    <?php 
                                                    echo htmlspecialchars($finalAverage);
                                                    ?>
                                                </td>
                                                <td class="text-center"  style="color: <?php echo ($finalAverage >= 75) ? 'green' : 'red'; ?>;">
                                                    <?php
                                                    if ($finalAverage >= 75) {
                                                        echo "PASSED";
                                                    } else {
                                                        echo "FAILED";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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

