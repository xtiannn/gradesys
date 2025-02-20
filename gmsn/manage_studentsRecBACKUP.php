<?php
include 'session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Students' Information</title>
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
    .custom-container {
      margin-left: -20px;
      margin-right: -15px;
    }
    .container {
      width: 100%; /* Adjust as necessary */
    }
    .import-excel{
        height: 30px;
    }
    .customer-container {
      margin-left: -60px;
      margin-right: -15px;
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

<?php 
require_once("support/header.php");
require_once("support/sidebar.php")?>
<body>
<main id="main" class="main">
        <section class="section">
            <div class="custom-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Students</li>
                    </ol>
                </nav>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <h6 class="custom-card-title"><i class="bi bi-card-text me-2"></i>
                                    Students' Information</h6>
                                </div>
                                <div class="d-flex align-items-center mb-0">
                                    <div class="import-excel">
                                    <form action="excel/gradesheet.php" method="POST" enctype="multipart/form-data" class="me-2">
                                        <div class="input-group">
                                        <input type="file" name="import_file" id="import_file" class="form-control form-control-sm" style="display: none;" />
                                        <label for="import_file" class="btn btn-outline-success btn-sm">
                                            Import Excel
                                        </label>
                                        <button type="submit" name="save_excel_data" style="height: 31px" class="btn btn-success btn-sm"><i class="bi bi-upload"></i></button>
                                        </div>
                                    </form>
                                    </div>
                                    <a href="inactive_students.php" class="btn btn-secondary btn-sm me-2">
                                        <i class="bi bi-trash"></i> Inactive
                                    </a>
                                    <a href="sh_studRecord.php" class="btn btn-primary btn-sm">
                                        <i class="bi bi-person-add"></i> New Student
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Table with stripped rows -->
                                <table class="table table-striped table-bordered datatable" style="font-size: 14px;">
                                    <thead>
                                        <?php 
                                            require_once("includes/config.php");
                                            $query = "SELECT *
                                            FROM students
                                            WHERE isActive = 1";
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
                                            <!-- <th style="width: 90px">Address</th> -->
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                                <td class="text-center"><?php echo htmlspecialchars($student['lrn']); ?></td>
                                                <td>
                                                    <?php 
                                                        $capitalizedLname = ucwords(strtolower($student['lname']));
                                                        $capitalizedFname = ucwords(strtolower($student['fname']));
                                                        $capitalizedMname = ucwords(strtolower($student['mname']));

                                                        echo htmlspecialchars($capitalizedLname . ', ' . $capitalizedFname . ' ' . $capitalizedMname);
                                                    ?>
                                                </td>
                                                                                                
                                                <td class="text-center"><?php echo date('m/d/Y', strtotime($student['dob'])); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($student['contact']); ?></td>
                                                <td class="text-center">
                                                    <div class="" role="group" aria-label="Student Actions">

                                                        <a class="btn btn-primary btn-sm" href="sh_studRecord_update.php?studID=<?php echo $student['studID']; ?>">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>

                                                        <button class="btn btn-danger btn-sm delete-btn" data-student-id="<?php echo $student['studID']; ?>">
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
  <script src="assets/jquery-3.7.1.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
    // Function to handle delete button click
    function handleDeleteButtonClick(studentId, studentName) {
    Swal.fire({
    title: 'Are you sure?',
    text: 'You are about to delete the student: ' + studentName,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
    if (result.isConfirmed) {
        // Send AJAX request to delete_student.php
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_student.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Display success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The student has been deleted successfully.',
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
                            text: 'Failed to delete the student: ' + response.message
                        });
                    }
                } else {
                    // Display error message if request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to delete the student. Please try again later.'
                    });
                }
            }
        };
        xhr.send('studentId=' + studentId); // Send the student ID to the server
        }
    });
}


// Attach event listener to delete buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission behavior
            var studentId = this.getAttribute('data-student-id');
            var studentName = this.closest('tr').querySelector('td:nth-child(3)').innerText; // Get the student name from the row
            handleDeleteButtonClick(studentId, studentName);
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if 'alert' query parameter is set
        const urlParams = new URLSearchParams(window.location.search);
        const alertType = urlParams.get('alert');

        if (alertType) {
            let title, text, icon;
            let timer = 0; // Default to no timer

            switch (alertType) {
                case 'student_added':
                    title = 'Success!';
                    text = 'Student Added.';
                    icon = 'success';
                    timer = 5000; // Show for 5 seconds
                    break;
                case 'student_not_saved':
                    title = 'Error!';
                    text = 'Student Not Saved.';
                    icon = 'error';
                    break;
                case 'lrn_exists':
                    title = 'Error';
                    text = 'The LRN is already registered to a different student. Please verify the LRN and try again.';
                    icon = 'error';
                    break;
                case 'file_upload_error':
                    title = 'Error';
                    text = 'Sorry, there was an error uploading your file.';
                    icon = 'error';
                    break;
                case 'invalid_file_type':
                    title = 'Error';
                    text = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed.';
                    icon = 'error';
                    break;
                case 'db_error':
                    title = 'Error';
                    text = 'A database error occurred.';
                    icon = 'error';
                    break;
                case 'general_error':
                    title = 'Error';
                    text = 'An error occurred.';
                    icon = 'error';
                    break;
                default:
                    return; // No alert to show
            }

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonText: 'OK',
                timer: timer,
                timerProgressBar: true
            }).then(() => {
                // Clear query parameters from the URL
                const url = new URL(window.location.href);
                url.searchParams.delete('alert');
                url.searchParams.delete('message'); // Remove any other parameters if needed
                window.history.replaceState({}, document.title, url.href);
            });
        }
    });
</script>

</body>


</html>