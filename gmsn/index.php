<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "includes/config.php";

if (!isset($_SESSION['userID'])) {    
  header("Location: /cap/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin Dashboard</title>
  <meta content="Grading system dashboard for Grace Montessori" name="description">
  <meta content="grading, dashboard, school, education, Grace Montessori" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/gmsnlogo.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    /* Custom CSS for dashboard cards */
    .card {
      height: 150px;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: scale(1.05);
    }

    .card-body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
    }

    .card-content {
      text-align: center;
    }

    .card-content span {
      font-family: 'Poppins', sans-serif;
      font-size: 1.2rem;
      font-weight: 500;
      color: #fff;
    }

    .card i {
      font-size: 2rem;
      color: #fff;
      transition: transform 0.3s ease;
    }
    #calendar {
    max-width: 1100px;
    margin: 40px auto;
}


  </style>
</head>

<body>
  <?php require_once("support/header.php")?>
  <?php require_once("support/sidebar.php")?>

  <main id="main" class="main">
    <div class="container">
      <div class="row">

      
      <!-- Card 1: Students -->
      <div class="col-lg-4 col-md-6">
        <a href="manage_studentsRec.php" style="text-decoration: none; color: inherit;">
          <div class="card bg-primary">
            <div class="card-body">
              <div class="card-content">
                <i class="bi bi-people"></i>
                <?php
                require_once ("includes/config.php");

                try {
                  $sql_student = "SELECT COUNT(*) as total_student FROM students";
                  $stmt = $conn->prepare($sql_student);
                  $stmt->execute();

                  // Fetch result
                  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  $total_student = $result[0]['total_student'];
                  echo "<span class='ms-3'>$total_student</span>";
                } catch (PDOException $e) {
                  echo "<span class='ms-3'>Error: " . $e->getMessage() . "</span>";
                }
                ?>
                <span class="ms-3">Students</span> 
              </div>
            </div>
          </div>
        </a>
      </div>


        <!-- Card 2: Faculty -->
        <div class="col-lg-4 col-md-6">
          <a href="faculty.php" style="text-decoration: none; color: inherit;">
            <div class="card bg-secondary">
              <div class="card-body">
                <div class="card-content">
                  <i class="bi bi-person"></i>
                  <?php
                  require_once ("includes/config.php");

                  try {
                    $sql_faculty = "SELECT COUNT(*) as total_faculty FROM faculty";
                    $stmt = $conn->prepare($sql_faculty);
                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $total_faculty = $result[0]['total_faculty'];
                    echo "<span class='ms-3'>$total_faculty</span>";
                  } catch (PDOException $e) {
                    echo "<span class='ms-3'>Error: " . $e->getMessage() . "</span>";
                  }
                  ?>
                  <span class="ms-3">Faculties</span> 
                </div>
              </div>
            </div>
          </a>
        </div>


        <!-- Card 3: Subjects -->
        <div class="col-lg-4 col-md-6">
          <a href="subjects.php" style="text-decoration: none; color: inherit;">
          <div class="card bg-info">
            <div class="card-body">
              <div class="card-content">
                <i class="bi bi-book"></i>
                <?php 
                  require_once("includes/config.php");
                  try {
                    $sql_subject = "SELECT COUNT(*) as total_subject FROM subjects";
                    $stmt = $conn->prepare($sql_subject);
                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $total_subject = $result[0]['total_subject'];
                    echo "<span class='ms-3'>$total_subject</span>";
                  } catch (PDOException $e) {
                    echo "<span class='ms-3'>Error:" . $e->getMessage() ."</span>";
                  }
                ?>
                <span class="ms-3">Subjects</span>
              </div>
            </div>
          </div></a>
        </div>

        <!-- Card 4: Programs -->
        <div class="col-lg-4 col-md-6">
          <a href="programs.php" style="text-decoration: none; color: inherit">
          <div class="card bg-warning">
            <div class="card-body">
              <div class="card-content">
                <i class="bi bi-calendar2-check"></i>
                <?php 
                try {
                  $sql_program = "SELECT COUNT(*) as total_program FROM programs";
                  $stmt = $conn->prepare($sql_program);
                  $stmt->execute();

                  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  $total_program = $result[0]['total_program'];
                  echo "<span class='ms-3'>$total_program</span>";
                } catch (PDOException $e) {
                  echo "<span class='ms-3'>Error:" .$e->getMessage()."</span>";
                }
                ?>
                <span class="ms-3">Programs</span>
              </div>
            </div>
          </div>
        </a>
        </div>

        <!-- Card 5: Curriculum -->
        <div class="col-lg-4 col-md-6">
          <a href="curri.php" style="text-decoration: none; color: inherit">
          <div class="card bg-danger">
            <div class="card-body">
              <div class="card-content">
                <i class="bi bi-journal"></i>
                <?php 
                  try {
                    $sql_curri = ("SELECT COUNT(*) as total_curri FROM academic_year");
                    $stmt =$conn->prepare($sql_curri);
                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $total_curri = $result[0]['total_curri'];
                    echo "<span class='ms-3'>$total_curri</span>";
                  } catch (PDOException $e) {
                    echo "<span class='ms-3'>".$e->getMessage()."</span>";
                  }
                ?>
                <span class="ms-3">Academic Year</span>
              </div>
            </div>
          </div>
          </a>
        </div>

        <!-- Card 6: Grade Levels -->
        <div class="col-lg-4 col-md-6">
          <a href="grade_level.php" style="text-decoration: none; color: inherit">
          <div class="card bg-success">
            <div class="card-body">
              <div class="card-content">
                <i class="bi bi-award"></i>
                <?php 
                  try {
                    $sql_gradelvl = ("SELECT COUNT(*) as total_gradelvl FROM grade_level WHERE isActive=1");
                    $stmt = $conn->prepare($sql_gradelvl);
                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $total_gradelvl = $result[0]['total_gradelvl'];
                    echo "<span class='ms-3'>$total_gradelvl</span>";
                  } catch (PDOException $e) {
                    echo "<span class='ms-3'>".$e->getMessage()."</span>";

                  }
                ?>
                <span class="ms-3">Grade Levels</span>
              </div>
            </div>
          </div>
          </a>
        </div>

        <div class="col-lg-12">
          <div id="calendar"></div>
        </div>

      </div>
    </div>
    
  </main>

  <?php require_once("support/footer.php")?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="assets/sweetalert2.all.min.js"></script> -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('fetch/fetch_events.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data); // Log the fetched data
                    successCallback(data); // Use successCallback to load events
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                    failureCallback(); // Use failureCallback if fetching fails
                });
        }
    });

    calendar.render();
});


  </script>

</body>

</html>
