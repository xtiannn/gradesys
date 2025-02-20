

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Academic Year</title>
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

<body>

  <?php //require_once"support/header.php"?>
  <?php //require_once"support/sidebar.php"?>
  <div id="alertContainer"></div>
    <main id="main" class="main">
        <section class="section">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">AY</li>
                    </ol>
                </nav>
                    <div class="row">
                        <div class="row">
                            <div class="col-10">
                                <h5 class="card-title">Academic Year</h5>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAYModal">
                                <i class="bi bi-plus-lg"></i>
                                    Add Session
                                </button>
                            </div>
                        </div>
                        <!-- <div class="col-md-10">
                            <p>This page will allow the admin to add, update, and delete AY.</p>
                        </div> -->
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Table with stripped rows -->
                                <table class="table datatable">
                                    <thead>
                                        <?php 
                                            require_once("includes/config.php");
                                            $query = "SELECT ay.*,s.semName FROM academic_year ay
                                            JOIN semester s ON ay.semID = s.semID ORDER BY ay.ayName DESC";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                                            $count = 1; 

                                        ?>
                                        <tr>
                                            <th>#</th>
                                            <th>Status</th>
                                            <th>Term</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($acadYear as $row): ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td><?php echo htmlspecialchars($row['ayName']); ?></td>
                                                <td><?php echo htmlspecialchars($row['semName']); ?></td>
                                                <td class="<?php echo $row['isActive'] == 0 ?'text-danger' : 'text-success';?> text-center">
                                                    <?php echo $row['isActive'] == 0 ? 'Inactive' : 'Active';?>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <a class="btn btn-primary btn-sm update-btn" href="#" name="txtUpdate" data-bs-toggle="modal" data-bs-target="#updateAYModal"
                                                        data-ay-id="<?php echo $row['ayID']; ?>" 
                                                        data-ay-session="<?php echo $row['ayName']; ?>"
                                                        data-ay-status="<?php echo $row['isActive']; ?>"
                                                        data-ay-term="<?php echo $row['semID']; ?>">
                                                            <i class="bi bi-pencil-square"></i> Update
                                                        </a>
                                                        <a class="btn btn-danger btn-sm delete-btn <?php echo ($row['isActive'] == 1) ? 'disabled' : ''; ?>"
                                                        href="#" 
                                                        data-ay-id="<?php echo $row['ayID']; ?>"
                                                        data-ay-status="<?php echo $row['isActive']; ?>"
                                                        <?php echo ($row['isActive'] == 1) ? 'disabled' : ''; ?>>
                                                            <i class="bi bi-trash"></i> Delete
                                                        </a>
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

       <?php //include"modals/AYModal.php"?>                                                

    </main><!-- End #main -->
    

        
  <?php require_once"support/footer.php"?>            

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>



  


  <script src="assets/sweetalert2.all.min.js"></script>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/jquery-3.7.1.min.js"></script>
  <script src="assets/js/main.js"></script>
   <!-- this is ajax.googleapis jquery3.5.1 -->
   <script src="assets/jquery.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const alertType = urlParams.get('alert');

        switch (alertType) {
            case 'success':
                Swal.fire({
                    icon: 'success',
                    title: 'Update Successful!',
                    text: 'The academic year has been updated successfully.',
                });
                break;
            case 'sem-changed':
                Swal.fire({
                    icon: 'info',
                    title: 'Semester Changed!',
                    text: 'The semester for this academic year has been updated.',
                });
                break;
            case 'no-changes':
                Swal.fire({
                    icon: 'warning',
                    title: 'No Changes Made',
                    text: 'No updates were necessary; the details remain unchanged.',
                });
                break;
            case 'db_error':
                const message = urlParams.get('message');
                Swal.fire({
                    icon: 'error',
                    title: 'Database Error',
                    text: 'An error occurred while updating: ' + message,
                });
                break;
            default:
                // No alert to show
                break;
        }
    });
</script>

</body>


</html>



