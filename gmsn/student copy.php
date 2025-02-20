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
  <script src="assets/jquery-3.7.1.min.js"></script>

  <!-- this is ajax.googleapis jquery3.5.1 -->
  <script src="assets/jquery.min.js"></script>

</head>

<body>

  <?php 
  require_once"support/header.php";
  require_once"support/sidebar.php";
  include("includes/config.php");

if(isset($_GET['subjectID']) && isset($_GET['programID'])) {
    $subID = $_GET['subjectID'];
    $subjectName = $_GET['subjectName'];
    $progID = $_GET['programID'];
    $gradelvlID = $_GET['gradelvlID'];
    $secID = $_GET['secID'];
    $secName = $_GET['secName'];
    $facultyName = $_GET['facultyName'];
    $facultyID = $_GET['facultyID'];
    $ayID = $_GET['ayID'];
    $semID = $_GET['semID'];

    $query = "SELECT ss.*, 
        (SELECT lrn FROM students s WHERE ss.studID = s.studID) as lrn,
        (SELECT ay.ayName FROM academic_year ay WHERE ay.ayID = ss.ayID) as ayName,
        (SELECT semName FROM semester WHERE semID = ss.semID) as semName,
        (SELECT subjectname FROM subjects WHERE subjectID = ss.subjectID) as subjects,
        (SELECT CONCAT(lname, ', ', fname, ' ', mname) FROM students s WHERE ss.studID = s.studID) as studname
          FROM section_students ss 
          WHERE ss.ayID = :ayID AND ss.subjectID = :subID AND ss.secID = :secID";

$stmt = $conn->prepare($query);
$stmt->bindParam(':ayID', $ayID, PDO::PARAM_INT);
$stmt->bindParam(':subID', $subID, PDO::PARAM_INT);
$stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
$stmt->execute();
$curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;

} else {
    echo "Not required parameters are set!";
}

?>
    <main id="main" class="main">
        <section class="section">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="section_builder.php">Sections</a></li>
                            <li class="breadcrumb-item">
                                <a href="manage_sec.php?secName=<?php echo $secName?>&programID=<?php echo $progID?>&secID=<?php echo $secID?>&gradelvlID=<?php echo $gradelvlID ?>&semID=<?php echo $semID ?>&ayID=<?php echo $ayID ?>">Manage Sections</a>
                            </li>
                        <li class="breadcrumb-item active" aria-current="page">Students</li>
                    </ol>
                </nav>  
                        <div class="row">
                            <div class="col-md-10">
                                <div class="float-end">
                                <form action="excel/gradesheet.php" method="POST" enctype="multipart/form-data">
                                    <div class="input-group">
                                        <input type="file" name="import_file" id="import_file" class="form-control form-control-sm" style="display: none;" />
                                        <label for="import_file" class="btn btn-outline-success btn-sm">
                                            Import Excel
                                        </label>
                                        <button type="submit" name="save_excel_data" style="height: 31px" class="btn btn-success btn-sm"><i class="bi bi-upload"></i></button>
                                    </div>                            
                                </form>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudent">
                                <i class="bi bi-person-add"></i> Enroll Student
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                            <h5 class="card-title"><?php echo $subjectName?></h5>
                            </div>
                        </div> 
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body pt-0">
                              <div class="m-0">
                                <span><h6 class="m-2 mt-4 fw-bold">Faculty: <?php echo $facultyName ?></h6></span>
                                <span><h6 class="m-2 fw-bold"><?php echo $secName ?></h6></span>
                              </div>
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>LRN</th>
                                            <th>Name</th>
                                            <th>A.Y.</th>
                                            <th>Term</th>
                                            <th><?php echo ($semID == 1) ? '1st Qtr' : '3rd Qtr'; ?></th>
                                            <th><?php echo ($semID == 1) ? '2nd Qtr' : '4th Qtr'; ?></th>
                                            <th>Final Grade</th>
                                            <th style="width: 132px" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($curriculum as $row): ?>
                                            <tr>
                                                <td class="text-center"><?php echo ++$count; ?></td> 
                                                <td><?php echo ($row['lrn']); ?></td>
                                                <td><?php echo ($row['studname'])?></td>
                                                <td><?php echo ($row['ayName']); ?></td>
                                                <td><?php echo ($row['semName']); ?></td>
                                                <td class="text-center" <?php if ($row['grade'] < 75) echo 'style="color: red;"'; ?>>
                                                    <?php 
                                                    if ($row['grade'] != 0) {
                                                        echo $row['grade'];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center" <?php if ($row['grade2'] < 75) echo 'style="color: red;"'; ?>>
                                                    <?php 
                                                    if ($row['grade2'] != 0) {
                                                        echo $row['grade2']; 
                                                    } 
                                                    ?>
                                                </td>
                                                <td class="text-center" <?php if ($row['fgrade'] < 75) echo 'style="color: red;"'; ?>>
                                                    <?php 
                                                    if ($row['grade'] != 0 && $row['grade2'] != 0) {
                                                        echo $row['fgrade']; 
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                <button type="button" class="btn btn-success grade-btn btn-sm" data-bs-toggle="modal" data-bs-target="#inputGradeModalSub" 
                                                data-stud-id="<?php echo $row['studID']; ?>"
                                                data-enroll-id="<?php echo $row['enrollID']; ?>"
                                                data-subject-id="<?php echo $row['subjectID']; ?>"
                                                data-subject-name="<?php echo $row['subjects']; ?>"
                                                data-grade-first="<?php echo $row['grade']; ?>"
                                                data-grade-second="<?php echo $row['grade2']; ?>"
                                                data-sec-id="<?php echo $secID ?>"
                                                data-sec-name="<?php echo $secName ?>"
                                                data-fac-id="<?php echo $facultyID ?>"
                                                data-fac-name="<?php echo $facultyName ?>"
                                                data-session-id="<?php echo $ayID ?>">
                                                Grade <i class="bi bi-plus-lg"></i> 
                                                </button>
                                                    <div class="btn-group">
                                                        <button class="btn btn-danger delete-btn btn-sm" data-stud-id="<?php echo $row['studID']; ?>" 
                                                        data-subject-id="<?php echo $row['subjectID']; ?>"
                                                        data-enroll-id="<?php echo $row['enrollID']; ?>">
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





        <?php include("modals/studentM.php")?>                                            
                                           


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
    // Function to handle enrollment when "Enroll" button is clicked
    $('.enroll-btn').click(function() {
      // Get the student ID from the data attribute
      var studentID = $(this).data('student-id');

      // AJAX request to enroll the student
      $.ajax({
        url: 'enroll_student.php',
        method: 'POST',
        data: { studID: studentID }, // Pass student ID as 'studID'
        success: function(response) {
          // Handle success response
          console.log('Student enrolled successfully.');
          // Optionally, you can reload the page to update the enrolled students list
          // location.reload();
        },
        error: function(xhr, status, error) {
          // Handle error
          console.error('Error enrolling student:', error);
        }
      });
    });

  </script>
  <script>
function handleDeleteButtonClick(enrollID, studName) {
    Swal.fire({
        title: 'Confirmation',
        text: 'Are you sure you want to delete ' + studName + ' ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_subject.php',
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
                        text: 'Failed to delete subject. Please try again later.'
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
            var studID = this.getAttribute('data-stud-id');
            var subjectID = this.getAttribute('data-subject-id');
            var studName = this.closest('tr').querySelector('td:nth-child(3)').innerText; 
            handleDeleteButtonClick(enrollID, studName);
        });
    });
});
</script>


</body>

</html>