<?php
include 'session.php';
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
<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>
  <?php 
    require_once("includes/config.php");
    $query = 
    "SELECT ss.*, s.lrn, CONCAT(s.lname, ', ', s.fname, s.mname) AS studname
    FROM section_students ss
    JOIN students s ON ss.studID = s.studID";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $count = 0;

    ?>
    <main id="main" class="main">
        <section class="section">
            <div class="custom-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Grades</li>
                    </ol>
                </nav>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <h6 class="custom-card-title"><i class="bi bi-star me-2"></i>Students' Grades</h6>
                                </div>
                            </div>
                            <div class="card-body">
                            <table class="table table-striped table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">LRN</th>
                                        <th>Name</th>
                                        <!-- <th class="text-center">Program</th> -->
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($curriculum as $row): 
                                         if (!in_array($row['studID'], $curriculum)):
                                            $curriculum[] = $row['studID'];
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo ++$count; ?>.</td> 
                                            <td class="text-center"><?php echo ($row['lrn']); ?></td>
                                            <td><?php echo ucwords(strtolower($row['studname'])); ?></td>
                                            <!-- <td class="text-center"><?php echo ($row['program']); ?></td> -->
                                            <td class="text-center">
                                                <a class="btn btn-primary btn-sm" type="button" href="students_subjRecord.php?studID=<?php echo $row['studID']; ?>&studName=<?php echo urlencode($row['studname']); ?>&studLRN=<?php echo urlencode($row['lrn']); ?>">
                                                    <i class="bi bi-gear"></i> Grades
                                                </a>
                                                <!-- <button class="btn btn-danger delete-btn btn-sm" data-enroll-id="<?php echo $row['enrollID']; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button> -->
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

       <?php include"modals/studentM.php"?>                                                

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
function handleDeleteButtonClick(enrollID, studName) {
    Swal.fire({
        title: 'Confirmation Required',
        text: 'You are about to delete the student:' + studName + ' ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_student.php',
                method: 'POST',
                data: { enrollID: enrollID }, 
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deletion Successful',
                        text: 'The student ' + studName + ' has been successfully deleted.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload(); 
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to delete student. Please try again later.'
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
            var studName = this.closest('tr').querySelector('td:nth-child(3)').innerText; 
            handleDeleteButtonClick(enrollID, studName);
        });
    });
});
</script>
</body>


</html>