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
     $fetchFac = "SELECT facultyID FROM faculty WHERE facultyNum = :facultyNum";
     $stmtFac = $conn->prepare($fetchFac);
     $stmtFac->bindParam(':facultyNum', $facultyNum, PDO::PARAM_STR);
     $stmtFac->execute();

     $resultFac = $stmtFac->fetch(PDO::FETCH_ASSOC);
      if($resultFac){
        $facultyID = $resultFac['facultyID'];

        $sqlSection = "SELECT s.*, sm.semName, p.programcode, gl.gradelvlcode
        FROM sections s 
        LEFT JOIN semester sm ON s.semID = sm.semID
        LEFT JOIN programs p ON s.programID = p.programID
        LEFT JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
        WHERE s.facultyID = :facultyID AND s.isActive=1 AND s.ayName != :ayName AND (s.semID != :semID OR s.semID IS NULL)
        ORDER BY s.ayName DESC, s.deptID ASC, s.semID DESC";
        $stmtSec = $conn->prepare($sqlSection);
        $stmtSec->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
        $stmtSec->bindParam(':ayName', $activeAyName, PDO::PARAM_STR);
        $stmtSec->bindParam(':semID', $activeSemName, PDO::PARAM_INT);
        $stmtSec->execute();

        $section = $stmtSec->fetchAll(PDO::FETCH_ASSOC);
      }else{
        echo "No facultyID found";
      }
  
  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Inactive Classes</title>
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
          <li class="breadcrumb-item"><a href="advisory.php">Sections</a></li>
          <li class="breadcrumb-item active">Inactive</li>
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
                        <i class="bi bi-archive me-2"></i> Inactive Classes
                    </h6>
                </div>
            </div>
            <div class="card-body">
              <table id="programTable" class="table table-bordered table-striped datatable">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 20px">#</th>
                    <th class="text-center">A.Y.</th>
                    <th>Program</th>
                    <th>Section</th>
                    <th>Semester</th>
                    <th class="text-center" style="width: 20px">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $count = 1;
                    foreach ($section as $row):
                  ?>
                  <tr>
                    <td class="text-center"><?php echo $count++?>.</td>
                    <td class="text-center"><?php echo $row['ayName']?></td>
                    <td>
                      <?php 
                       if ($row['deptID'] == 3){
                        echo $row['programcode'];
                       }elseif ($row['deptID'] == 2){
                        echo 'JHS';
                       }else{
                        echo 'Elementary';
                       }
                      ?>
                    </td>
                    <td><?php echo $row['gradelvlcode']. ' - ' .ucwords(strtolower($row['secName']))?></td>
                    <td><?php echo $row['semName'] == null ? 'N/A' : $row['semName']?></td>
                    <td class="text-center">
                      <a type="button" role="button" 
                      href="manage_students.php?secID=<?php echo $row['secID']?>&facultyID=<?php echo $row['facultyID']?>&semID=<?php echo $row['semID']?>&programID=<?php echo $row['programID']?>&gradelvlID=<?php echo $row['gradelvlID']?>&deptID=<?php echo $row['deptID']?>" 
                      class="btn btn-primary btn-sm">
                        <i class="bi bi-people"></i>
                      </a>
                    </td>
                  </tr>
                  <?php endforeach;?>
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