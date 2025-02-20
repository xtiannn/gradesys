<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Grade Level</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/gmsnlogo.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>

    <main id="main" class="main">
        <section class="section">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Grade Level</li>
                    </ol>
                </nav>
                    <div class="row">
                        <div class="row">
                            <div class="col-10">
                                <h5 class="card-title">Grade Level</h5>
                            </div>
                            <div class="col-md-2">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGradelvlModal">
                            <i class="bi bi-journal-plus"></i>  Add Level
                            </button>  
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Table with stripped rows -->
                                <table class="table datatable">
                                    <thead>
                                        <?php 
                                            require_once("includes/config.php");
                                            $query = "SELECT * FROM grade_level WHERE isActive=1";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $gradelvl = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                            $count = 0;
                                        ?>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th style="width: 259px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($gradelvl as $row): ?>
                                            <tr>
                                                <td><?php echo ++$count; ?></td>
                                                <td><?php echo strtoupper($row['gradelvlcode']); ?></td>
                                                <td><?php echo ucfirst(strtolower($row['gradelvl'])); ?></td>
                                                <td>
                                                    <div class="text-center">
                                                        <button class="btn btn-primary btn-sm updateGradelvl-btn" style="width: 50px" data-bs-toggle="modal" data-bs-target="#updateGradelvlModal" 
                                                        data-gradelvl-id="<?php echo $row['gradelvlID']; ?>"
                                                        data-gradelvl-code="<?php echo $row['gradelvlcode']; ?>"
                                                        data-gradelvl-name="<?php echo $row['gradelvl']; ?>">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button class="btn btn-danger delete-btn btn-sm" style="width: 50px" data-gradelvl-id="<?php echo $row['gradelvlID']; ?>">
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
        
       <?php include"modals/gradelvlModal.php"?>                                                  

    </main><!-- End #main -->
    

  <?php require_once"support/footer.php"?>            

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <script src="assets/sweetalert2.all.min.js"></script>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

 
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Add event listener to update buttons
        const updateButtons = document.querySelectorAll('.update-btn');
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Fetch the gradelvl ID
                const gradelvlID = this.getAttribute('data-gradelvl-id');

                // Fetch grade level data using AJAX
                fetch('fetch/fetch_gradelvl.php?gradelvlID=' + gradelvlID)
                    .then(response => response.json())
                    .then(data => {
                        // Populate modal fields with data
                        document.getElementById('update_ID').value = data.gradelvlID;
                        document.getElementById('gradelvlcode').value = data.gradelvlcode;
                        document.getElementById('gradelvlname').value = data.gradelvl;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>

</html>