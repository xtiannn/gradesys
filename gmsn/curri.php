<?php
include 'session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Programs</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/gmsnlogo.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
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
    .custom-container {
      margin-left: -10px;
      margin-right: -15px;
      margin-top: -60px;
    }
    .custom-container {
      width: -100%; 
    }
    
  </style>
</head>

<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>

    <main id="main" class="main">
        <section class="section">
            <div class="custom-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Programs</li>
                    </ol>
                </nav>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Table with stripped rows -->
                                <?php 
                                    require_once("includes/config.php");

                                    $query = 
                                        "SELECT p.* 
                                        FROM programs p 
                                        LEFT JOIN department d ON p.programID = d.deptID
                                        WHERE p.isActive = 1 
                                        ORDER BY 
                                            CASE 
                                                WHEN p.deptID = 3 THEN 1 
                                                WHEN p.deptID = 2 THEN 2 
                                                ELSE 3 
                                            END,
                                            p.programname ASC
                                    ";

                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);  

                                    $count = 0;
                                    ?>
                                <table class="table datatable table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($programs as $program): ?>
                                            <tr class="table-row-link">
                                                <td class="text-center"><?php echo ++$count; ?>.</td> <!-- Increment count for each program -->
                                                <td>
                                                    <?php echo ($program['programcode']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($program['programname']); ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?php echo ($program['deptID'] == 2) ? 'curriculumJHS.php' : 'curriculum.php'; ?>?program_id=<?php echo $program['programID']; ?>&deptID=<?php echo $program['deptID']; ?>" 
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bi bi-gear me-1"></i>
                                                        Manage
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <!-- End Table with stripped rows -->

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


</body>

</html>