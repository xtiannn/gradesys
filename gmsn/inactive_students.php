<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Students</title>
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

    <main id="main" class="main mt-0">
        <section class="section">
            <div class="custom-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="manage_studentsRec.php">Students</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inactive Students</li>
                    </ol>
                </nav>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <h6 class="custom-card-title">
                                    <i class="bi bi-person-x-fill me-2"></i>Inactive Students
                                    </h6>
                                </div>
                            </div>
                        <div class="card-body">
                        <!-- Table with stripped rows -->
                        <table class="table table-bordered table-striped datatable">
                        <thead>
                                <?php 
                                    require_once("includes/config.php");
                                    $query = "SELECT *
                                    FROM students
                                    WHERE isActive = 0";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                    $count = 0;
                                ?>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">LRN</th>
                                    <th>Name</th>
                                    <th class="text-center">Birth Date</th>
                                    <th class="text-center">Contact No.</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td class="text-center"><?php echo ++$count; ?>.</td>
                                        <td class="text-center"><?php echo htmlspecialchars($student['lrn']); ?></td>
                                        <td><?php echo htmlspecialchars(ucwords(strtolower(trim($student['lname']))).', '.ucwords(strtolower(trim($student['fname']))).' '.ucwords(strtolower(trim($student['mname'])))); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($student['dob']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($student['contact']); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-primary retrieve-btn btn-sm" data-student-id="<?php echo $student['studID']; ?>">
                                            <i class="bi bi-recycle"></i>
                                            </button>
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
        // Function to handle delete button click
        function handleRetrieveButtonClick(studentId, studentName) {
        Swal.fire({
        title: 'Confirmation Required',
        text: 'You are about to retrieve the student: ' + studentName,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Retrieve'
        }).then((result) => {
        if (result.isConfirmed) {
            // Send AJAX request to delete_student.php
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'retrieve_student.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            // Display success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Retrieved!',
                                text: 'The student has been retrieved successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // Reload the page or update the table if needed
                                location.reload(); // Reload the page
                                // You can update the table without reloading the page
                                // Example: this.closest('tr').remove();
                            });
                        } else {
                            // Display error message if deletion fails
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to retrieve the student: ' + response.message
                            });
                        }
                    } else {
                        // Display error message if request fails
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Failed to retrieve the student. Please try again later.'
                        });
                    }
                }
            };
            xhr.send('studentId=' + studentId); // Send the student ID to the server
            }
        });
    }


    // Attach event listener to retrieve buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.retrieve-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default form submission behavior
                var studentId = this.getAttribute('data-student-id');
                var studentName = this.closest('tr').querySelector('td:nth-child(3)').innerText; // Get the student name from the row
                handleRetrieveButtonClick(studentId, studentName);
            });
        });
    });
</script>

</body>

</html>