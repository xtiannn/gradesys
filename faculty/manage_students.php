<?php 
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
  require_once("includes/config.php");



  $secID = $_GET['secID'] ?? '' ;
  $facultyID = $_GET['facultyID'] ?? '' ;
  $semID = $_GET['semID'] ?? '' ;
  $programID = $_GET['programID'] ?? '' ;
  $gradelvlID = $_GET['gradelvlID'] ?? '' ;
  $deptID = $_GET['deptID'] ?? '' ;
  try {
      // $sqlAdvStuds = "SELECT ss.studID, ay.ayName, st.lrn,
      // CONCAT(st.lname, ', ', st.fname, ' ', IFNULL(st.mname, '')) AS studName
      // FROM section_students ss
      // JOIN academic_year ay ON ss.ayID = ay.ayID
      // JOIN students st ON ss.studID = st.studID
      // WHERE ss.secID = :secID AND ss.subjectID IS NULL";

      if($deptID == 3){
        $sqlAdvStuds = "SELECT ss.*, s.lrn, s.lname, s.fname, s.mname, p.programcode, sm.semCode
                      FROM section_students ss
                      JOIN students s ON ss.studID = s.studID
                      JOIN programs p ON ss.programID = p.programID
                      JOIN semester sm ON ss.semID = sm.semID
                      WHERE ss.semID = :semID
                      AND ss.gradelvlID = :gradelvlID
                      AND secID = :secID
                      ORDER BY s.lname ASC";

        $stmtAdvStuds = $conn->prepare($sqlAdvStuds);
        $stmtAdvStuds->bindParam(':semID', $semID, PDO::PARAM_INT);
        $stmtAdvStuds->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
        $stmtAdvStuds->bindParam(':secID', $secID, PDO::PARAM_INT);
        $stmtAdvStuds->execute();

      }else{
        $sqlAdvStuds = "SELECT ss.*, s.lrn, s.lname, s.fname, s.mname
                  FROM section_students ss
                  JOIN students s ON ss.studID = s.studID
                  WHERE ss.gradelvlID = :gradelvlID
                  AND secID = :secID
                  ORDER BY s.lname ASC";

        $stmtAdvStuds = $conn->prepare($sqlAdvStuds);
        $stmtAdvStuds->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
        $stmtAdvStuds->bindParam(':secID', $secID, PDO::PARAM_INT);
        $stmtAdvStuds->execute();
      }
      


      $students = $stmtAdvStuds->fetchAll(PDO::FETCH_ASSOC);

      $processedStudIDs = []; // Array to track processed studIDs

  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Advisory Class</title>
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
          <li class="breadcrumb-item active">Student List</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <?php 
      $secID = $_GET['secID'] ?? '';
      try {
        $sqlSecName = "SELECT s.secName,
        (SELECT programcode FROM programs WHERE programID = s.programID) as programcode,
        (SELECT gradelvlcode FROM grade_level WHERE gradelvlID = s.gradelvlID) as gradelvlcode
        FROM sections s WHERE secID = :secID";
        
        $stmtSec = $conn->prepare($sqlSecName);
        $stmtSec->bindParam(':secID', $secID, PDO::PARAM_INT);
        $stmtSec->execute();

        $result = $stmtSec->fetch(PDO::FETCH_ASSOC);
        
        $secName = ucwords(strtolower($result['secName']));
        $programCode = $result['programcode']; 
        $gradelvlCode = $result['gradelvlcode'];

        $sectionName = "$programCode $gradelvlCode - $secName";

      } catch (\Throwable $e) {
        echo "Error: " . $e->getMessage();
      }
    ?>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column">
                    <h6 class="custom-card-title">
                    <i class="bi bi-list me-2"></i> <?php echo $sectionName?>
                    </h6>
                </div>
              </div>
            <div class="card-body">
              <table id="programTable" class="table table-bordered table-striped datatable">
                <thead>
                  <?php if ($deptID ==3 ):?>
                    <tr>
                    <th class="text-center" style="width: 20px">#</th>
                    <th class="text-center">A.Y.</th>
                    <th class="text-center">LRN</th>
                    <th>Name</th>
                    <th class="text-center">Program</th>
                    <th class="text-center">Term</th>
                    <th class="text-center" style="width: 20px">Actions</th>
                  </tr>
                  <?php else:?>
                    <tr>
                    <th class="text-center" style="width: 20px">#</th>
                    <th class="text-center">A.Y.</th>
                    <th class="text-center">LRN</th>
                    <th>Name</th>
                    <th class="text-center" style="width: 20px">Actions</th>
                  </tr>
                  <?php endif;?>
                </thead>
                <?php 
                  $fetchAyName = "SELECT ayName FROM sections WHERE secID = :secID";
                  $stmtAyName = $conn->prepare($fetchAyName);
                  $stmtAyName->execute([':secID' => $secID]);
                  $result = $stmtAyName->fetch(PDO::FETCH_ASSOC);

                  $ayName = $result['ayName'];
                ?>
                <tbody>
                  <?php 
                    $count = 0;
                    foreach ($students as $stud): 
                      // Skip duplicate studID
                        if (in_array($stud['studID'], $processedStudIDs)) {
                          continue; // Skip this row if studID already processed
                      }

                      // Add studID to processed list
                      $processedStudIDs[] = $stud['studID'];
                  ?>
                  <?php if ($deptID == 3):?>
                    <tr>
                      <td class="text-center"><?php echo ++$count; ?>.</td>
                      <td class="text-center"><?php echo $ayName; ?></td>
                      <td class="text-center"><?php echo $stud['lrn']; ?></td>
                      <td>
                          <?php echo ucwords(strtolower($stud['lname'])) . ', ' . ucwords(strtolower($stud['fname'])) . ' ' . ucwords(strtolower($stud['mname'])); ?>
                      </td>              
                      <td class="text-center"><?php echo $stud['programcode']?></td>        
                      <td class="text-center"><?php echo $stud['semCode']?></td>        
                      <td class="text-center">
                        <a style="width: 40px; height: auto" href="stud_subject.php?studID=<?php echo $stud['studID']?>&facultyID=<?php echo $facultyID?>&secID=<?php echo $secID?>&semID=<?php echo $stud['semID']?>&programID=<?php echo $programID?>&gradelvlID=<?php echo $gradelvlID?>&deptID=<?php echo $deptID?>&subjectID=0" 
                        type="button" 
                        class="btn btn-primary btn-sm">
                          <i class="bi bi-book"></i> 
                        </a>
                      </td>
                    </tr>
                  <?php else:?>
                    <tr>
                      <td class="text-center"><?php echo ++$count; ?>.</td>
                      <td class="text-center"><?php echo $ayName; ?></td>
                      <td class="text-center"><?php echo $stud['lrn']; ?></td>
                      <td>
                          <?php echo ucwords(strtolower($stud['lname'])) . ', ' . ucwords(strtolower($stud['fname'])) . ' ' . ucwords(strtolower($stud['mname'])); ?>
                      </td>              
                      <td class="text-center">
                        <a style="width: 40px; height: auto" href="stud_subject.php?studID=<?php echo $stud['studID']?>&facultyID=<?php echo $facultyID?>&secID=<?php echo $secID?>&programID=<?php echo $programID?>&gradelvlID=<?php echo $gradelvlID?>&deptID=<?php echo $deptID?>&deptID=<?php echo $deptID?>&subjectID=0" 
                        type="button" 
                        class="btn btn-primary btn-sm">
                          <i class="bi bi-book"></i> 
                        </a>
                      </td>
                    </tr>
                  <?php endif?>
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