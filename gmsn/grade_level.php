<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Grade Level Entry</title>
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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <h6 class="custom-card-title">
                                    <i class="bi bi-bar-chart-line mr-2"></i> Grade Level Management
                                </h6>                            
                            </div>
                            <div class="d-flex align-items-center mb-0">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGradelvlModal">
                            <i class="bi bi-journal-plus mr-1"></i>  Add Level
                            </button>  
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered datatable" id="datatable">
                                <thead>
                                    <?php 
                                        require_once("includes/config.php");
                                        $query = "SELECT * FROM grade_level WHERE isActive IS NOT NULL ORDER BY isActive DESC, CAST(gradelvl AS UNSIGNED) ASC";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $gradelvl = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                        $count = 0;
                                    ?>
                                    <tr>
                                        <th style="width: 50px" class="text-center">#</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th class="text-center">Status</th>
                                        <th style="width: 150px" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($gradelvl as $row): ?>
                                        <tr>
                                            <td class="text-center"><?php echo ++$count; ?>.</td>
                                            <td><?php echo strtoupper($row['gradelvlcode']); ?></td>
                                            <td><?php echo ucfirst(strtolower($row['gradelvl'])); ?></td>
                                            <td class="text-center">
                                                <?php 
                                                    if($row['isActive'] == 1){
                                                        echo '<span class="badge badge-success">Active</span>';
                                                    }else{
                                                        echo '<span class="badge badge-warning">Inactive</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <button class="btn btn-primary btn-sm updateGradelvl-btn" style="width: 50px" data-bs-toggle="modal" data-bs-target="#updateGradelvlModal" 
                                                    data-gradelvl-id="<?php echo $row['gradelvlID']; ?>"
                                                    data-gradelvl-code="<?php echo $row['gradelvlcode']; ?>"
                                                    data-gradelvl-name="<?php echo $row['gradelvl']; ?>"
                                                    data-program-status="<?php echo $row['isActive']; ?>"
                                                    >
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <!-- <button class="btn btn-danger delete-btn btn-sm" style="width: 50px" data-gradelvl-id="<?php //echo $row['gradelvlID']; ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button> -->
                                                </div>
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
    <?php include"modals/gradelvlModal.php"?>  
  
  <?php require_once"support/footer.php"?>            

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <script src="assets/sweetalert2.all.min.js"></script>

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
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if(status === 'success'){
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'The Grade Level has been successfully added.',
                showConfirmButton: true,
                timer: 5000
            });
        }else if (status === 'updated'){
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'The Grade Level has been successfully updated.',
                showConfirmButton: true,
                timer: 5000
            });
        }else if (status === 'nochanges'){
            Swal.fire({
                icon: 'info',
                title: 'Notice!',
                text: 'No changes were made to the Grade Level.',
                showConfirmButton: true,
            });
        }else if (status === 'duplicate'){
            Swal.fire({
                icon: 'warning',
                title: 'Duplicate Entry!',
                text: 'The grade level code or name you entered already exists in the system. Please provide unique details.',                
                showConfirmButton: true,
            });
        }
        
        
        function removeUrlParameter(key) {
            if(history.replaceState){
                var url = window.location.href;
                var cleanedUrl = url.replace(new RegExp('[?&]' + key + '=[^&#]*(#.*)?$'), '$1').replace(/[?&]$/, '');
                history.replaceState({}, document.title, cleanedUrl);
            }
        }
        window.addEventListener('load', function() {
            removeUrlParameter('status');
        });
    });
  </script>

</body>

</html>c