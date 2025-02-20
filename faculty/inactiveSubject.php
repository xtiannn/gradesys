<?php 
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
  require_once("includes/config.php");

  try {
    // Corrected SQL query to select the academic year name
    $fetchAY = "SELECT ayName, semID FROM academic_year";
    $stmtAY = $conn->prepare($fetchAY);
    $stmtAY->execute();

    $resultAY = $stmtAY->fetch(PDO::FETCH_ASSOC);
    $activeAyName = $resultAY['ayName'];
    $activeSemName = $resultAY['semID'];
  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
  }


  $facultyNum = $_SESSION['userID'];

  try {
      // Fetch facultyID for the specific user
      $sqlFacultyID = "SELECT facultyID FROM faculty WHERE facultyNum = :facultyNum";
      
      $stmtFacultyID = $conn->prepare($sqlFacultyID);
      $stmtFacultyID->bindParam(':facultyNum', $facultyNum, PDO::PARAM_STR);
      $stmtFacultyID->execute();
      
      $facultyIDResult = $stmtFacultyID->fetch(PDO::FETCH_ASSOC);
      
      $facultyID = $facultyIDResult['facultyID'];

      $_SESSION['facultyID'] = $facultyID;
          
          // Fetch assigned subjects for the facultyID
          $sqlSubjects = 
          "SELECT s.subjectID, 
          s.subjectname, 
          p.programID, 
          p.programcode, 
          sc.secID, 
          sc.secName, 
          g.gradelvlcode, g.gradelvlID, fa.ayName, fa.facultyAssignID,
          fa.schedule,
          sc.deptID,
          sc.isActive,
          fa.ayName,
          fa.semID
          FROM facultyAssign fa
          LEFT JOIN subjects s ON fa.subjectID = s.subjectID
          LEFT JOIN programs p ON fa.programID = p.programID
          LEFT JOIN sections sc ON fa.secID = sc.secID
          LEFT JOIN grade_level g ON fa.gradelvlID = g.gradelvlID
          WHERE fa.facultyID = :facultyID AND sc.isActive=1 AND (fa.ayName != :ayName OR fa.semID != :semID)";

          $stmtSubjects = $conn->prepare($sqlSubjects);
          $stmtSubjects->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
          $stmtSubjects->bindParam(':ayName', $activeAyName, PDO::PARAM_STR);
          $stmtSubjects->bindParam(':semID', $activeSemName, PDO::PARAM_STR);
          $stmtSubjects->execute();
          $subjects = $stmtSubjects->fetchAll(PDO::FETCH_ASSOC);


  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      $subjects = []; 
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Archived Subjects</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../gmsn/assets/img/gmsnlogo.png" rel="icon">
  <link href="../gmsn/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../gmsn/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../gmsn/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
    td,th{
        font-size: 14px;
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
    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="assignedSub.php">Assigned Subject</a></li>
          <li class="breadcrumb-item active">Archived Subjects</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column">
                    <h6 class="custom-card-title">
                    <i class="bi bi-archive me-2"></i> Archived Subjects
                    </h6>
                </div>
              </div>
            <div class="card-body">
              <table id="programTable" class="table table-bordered table-striped datatable">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">A.Y.</th>
                    <th>Subject</th>
                    <th class="text-center">Program</th>
                    <th class="text-center">Section</th>
                    <th>Schedule</th>
                    <th class="text-center" style="width: 20px">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $count = 0;
                  foreach ($subjects as $subject): ?>
                    <tr>
                      <td class="text-center"><?php echo ++$count; ?>.</td>
                      <td class="text-center" style="white-space: nowrap"><?php echo htmlspecialchars($subject['ayName']); ?></td>
                      <td><?php echo htmlspecialchars($subject['subjectname']); ?></td>
                      <td class="text-center"><?php echo htmlspecialchars($subject['programcode'] != NULL) ? $subject['programcode'] : '-'; ?></td>
                      <td style="white-space: nowrap">
                        <?php 
                        $gradelevelname = $subject['gradelvlcode'] .' - ' . ucwords(strtolower($subject['secName'])); 
                        $programcode = $subject['programcode'];
                        echo $gradelevelname;

                        $secName = urlencode(trim("$programcode $gradelevelname"));
                        ?>
                      </td>
                      <td>
                          <?php 
                              $schedule = htmlspecialchars($subject['schedule']); 
                              $daysEntries = !empty($schedule) ? explode(', ', $schedule) : []; // Split the schedule into days
                              $isLongSchedule = count($daysEntries) > 1; // Show ellipsis if more than 1 entry
                              
                              $shortSchedule = $isLongSchedule ? $daysEntries[0] . '...' : implode('<br>', $daysEntries); // Show only the first entry with ellipsis if long
                          ?>
                          <div class="short-schedule" style="cursor: pointer;" onclick="toggleSchedule(this)">
                              <?php echo $shortSchedule; ?>
                          </div>
                          
                          <?php if ($isLongSchedule): ?>
                              <div class="full-schedule" style="display: none;">
                                  <?php 
                                      // Show the full schedule line by line
                                      echo nl2br(implode('<br>', $daysEntries)); 
                                  ?>
                              </div>
                          <?php endif; ?>
                      </td>
                      <script>
                        function toggleSchedule(element) {
                            const fullSchedule = element.nextElementSibling;
                            
                            if (fullSchedule.style.display === "none" || fullSchedule.style.display === "") {
                                fullSchedule.style.display = "block"; 
                                element.style.display = "none"; 
                            } else {
                                fullSchedule.style.display = "none"; le
                                element.style.display = "block"; 
                            }
                        }
                      </script>
                      <td class="text-center">
                        <a style="width: 40px; height: auto" href="students.php?subjectID=<?php echo $subject['subjectID']?>&facultyID=<?php echo $facultyID?>&faID=<?php echo $subject['facultyAssignID']?>&secID=<?php echo $subject['secID']?>&semID=<?php echo ($subject['semID'] != NULL) ? $subject['semID'] : NULL?>&deptID=<?php echo $subject['deptID']?>" 
                          type="button" 
                          class="btn btn-primary btn-sm">
                          <i class="bi bi-people-fill"></i> 
                        </a>
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
    
  <?php require_once"support/footer.php"?>            

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>



  


  <script src="assets/sweetalert2.all.min.js"></script>

  <!-- Vendor JS Files -->
  <script src="../gmsn/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../gmsn/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../gmsn/assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
  <script src="../gmsn/assets/jquery-3.7.1.min.js"></script>
  <script src="../gmsn/assets/js/main.js"></script>
   <!-- this is ajax.googleapis jquery3.5.1 -->
   <script src="../gmsn/assets/jquery.min.js"></script>


</body>


</html>