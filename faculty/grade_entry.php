<?php 
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
  require_once("includes/config.php");


  // Fetch data for dropdowns
  try {
      // Fetch students
      $studentsQuery = "SELECT studID, lname, fname, mname FROM students";
      $stmt = $conn->prepare($studentsQuery);
      $stmt->execute();
      $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Fetch subjects
      $subjectsQuery = "SELECT subjectID, subjectname FROM subjects";
      $stmt = $conn->prepare($subjectsQuery);
      $stmt->execute();
      $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Enter Grades</title>
  <meta content="Grading system for Grace Montessori" name="description">
  <meta content="grading, school, education, Grace Montessori" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/gmsnlogo.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
</head>

<body>
  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Enter Grades</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Enter Grades</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Enter Student Grades</h5>

            <!-- Grades Form -->
            <form id="gradeForm">
              <div class="row mb-3">
                <label for="studentSelect" class="col-sm-2 col-form-label">Student</label>
                <div class="col-sm-10">
                  <select class="form-select" id="studentSelect" name="studentID" required>
                    <option value="">Select Student</option>
                    <?php foreach ($students as $student): ?>
                      <option value="<?php echo $student['studID']; ?>">
                        <?php echo $student['lname'] . ', ' . $student['fname'] . ' ' . $student['mname']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="subjectSelect" class="col-sm-2 col-form-label">Subject</label>
                <div class="col-sm-10">
                  <select class="form-select" id="subjectSelect" name="subjectID" required>
                    <option value="">Select Subject</option>
                    <?php foreach ($subjects as $subject): ?>
                      <option value="<?php echo $subject['subjectID']; ?>">
                        <?php echo $subject['subjectname']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="gradeInput" class="col-sm-2 col-form-label">Grade</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="gradeInput" name="grade" min="0" max="100" required>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form><!-- End Grades Form -->

          </div>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->

<?php require_once"support/footer.php"?>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/sweetalert2.all.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

<script>
    $(document).ready(function() {
      $("#gradeForm").on("submit", function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
          url: "process/save_grade.php",
          type: "POST",
          data: formData,
          success: function(response) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Grade submitted successfully!',
              position: 'top',
              toast: true,
              showConfirmButton: false,
              timer: 2000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            });
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'An error occurred while submitting the grade.',
              position: 'top',
              toast: true,
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            });
          }
        });
      });
    });
  </script>


</body>

</html>
