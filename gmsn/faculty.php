<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Faculty Management</title>
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

</script>
  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>

    <main id="main" class="main">
        <section class="section">
            <div class="custom-container">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <h6 class="custom-card-title">
                                <i class="bi bi-person-workspace me-2"></i>Faculty Management
                                </h6>
                            </div>
                            <div class="d-flex align-items-center mb-0">
                            <a style="width: 120px" href="userCreate.php?userTypeID=2" type="button" class="btn btn-primary btn-sm">
                                <i class="bi bi-person-plus-fill"></i>
                                Add Faculty
                            </a>  
                            <!-- <button style="width: 120px" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                                <i class="bi bi-person-plus-fill"></i>
                                Add Faculty
                            </button>   -->
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Table with stripped rows -->
                            <table class="table table-striped table-bordered datatable">
                                <thead>
                                    <?php 
                                        require_once "includes/config.php";
                                        $query = "SELECT *  
                                        FROM faculty
                                        WHERE isActive = 1";                                           
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                        $count = 0;
                                    ?>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($faculties as $faculty): 
                                            $lname = ucwords(strtolower(trim($faculty['lname'])));
                                            $fname = ucwords(strtolower(trim($faculty['fname'])));
                                            $mname = ucwords(strtolower(trim($faculty['mname'])));

                                            $facname = "$lname, $fname $mname";
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo ++$count; ?>.</td>
                                            <td><?php echo htmlspecialchars(trim($faculty['facultyNum'])); ?></td>
                                            <td><?php echo $facname; ?></td>
                                            <td><?php echo htmlspecialchars($faculty['email']); ?></td>
                                            <td>
                                                <div class="text-center">
                                                    <a class="btn btn-primary btn-sm update-btn" style="width: 50px" href="userCreate.php?userID=<?php echo $faculty['facultyID']?>">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button class="btn btn-danger delete-btn btn-sm" style="width: 50px" data-faculty-id="<?php echo $faculty['facultyID']; ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
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
        <?php include("modals/facultyModal.php")?>                                            
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