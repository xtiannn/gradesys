<?php
    include 'session.php'; 

    require_once "includes/config.php"; 
    require_once "fetch/fetch_activeAy.php"; 

    $deptID = $_GET['deptID'];
    try {
        
        $sqlSec = "SELECT sc.*, p.programcode, sm.semCode, f.lname, f.fname, f.mname, f.gender, gl.gradelvlcode
        FROM sections sc
        LEFT JOIN programs p ON sc.programID = p.programID
        LEFT JOIN semester sm ON sc.semID = sm.semID
        JOIN grade_level gl ON sc.gradelvlID = gl.gradelvlID
        JOIN faculty f ON sc.facultyID = f.facultyID
        WHERE sc.deptID = :deptID AND (sc.isActive = 0 OR sc.ayName != :activeAy)
        ORDER BY sc.ayName ASC, sc.semID DESC, sc.programID ASC, gl.gradelvlcode ASC";

        $stmtSec = $conn->prepare($sqlSec);
        $stmtSec->bindParam(':deptID', $deptID, PDO::PARAM_INT); 
        $stmtSec->bindParam(':activeAy', $activeAY, PDO::PARAM_INT); 
        $stmtSec->execute(); 

        $sections = $stmtSec->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
        echo 'Error Fetching sections: ' . $e->getMessage();
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Inactive Sections</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/gmsnlogo.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>
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
<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>

    <main id="main" class="main">
        <section class="section">
            <div class="custom-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item " aria-current="page"><a href="section_builder.php">Sections</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inactive Sections</li>
                    </ol>
                </nav>
                <div class="row">
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <h6 class="custom-card-title">
                                <i class="bi bi-archive me-2"></i>
                                Inactive Sections
                                </h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered datatable">
                               <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">A.Y.</th>
                                    <?php if($deptID == 3):?>
                                        <th class="text-center">Term</th>
                                        <th>Program</th>
                                    <?php endif?>
                                    <th>Section</th>
                                    <th>Adviser</th>
                                    <th class="text-center">Action</th>
                                </tr>
                               </thead>
                               <tbody>
                               <?php 
                                    foreach ($sections as $index => $row):
                                        $lname = $row['lname'];
                                        $fname = $row['fname'];
                                        $mname = $row['mname'];
                                        $gender = $row['gender'];
                                        
                                        $initials = strtoupper(substr($fname, 0, 1)) . '.';
                                        $prefix = ($gender === 'Female') ? 'Ms. ' : 'Mr. ';
                                        $formattedAdviser = $prefix . ' ' . $lname . ' ' . $initials;

                                        $gl = $row['gradelvlcode'];
                                        $sec = ucwords(strtolower($row['secName']));
                                        $secName = "$gl - $sec";
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $index+1?>.</td>
                                    <td class="text-center"><?php echo $row['ayName']?></td>
                                    <?php if($deptID == 3):?>
                                        <td class="text-center"><?php echo $row['semCode']?></td>
                                        <td><?php echo $row['programcode']?></td>
                                    <?php endif;?>
                                    <td><?php echo $secName?></td>
                                    <td><?php echo ucwords(strtolower($formattedAdviser))?></td>
                                    <td class="text-center">
                                        <a href="enrolled_students.php?secID=<?php echo $row['secID']?>&gradelvlID=<?php echo $row['gradelvlID']?>&ayID=<?php echo $row['ayID']?>&facultyID=<?php echo $row['facultyID']?>&programID=<?php echo $row['programID']?>&semID=<?php echo $row['semID']?>&deptID=<?php echo $deptID?>" 
                                            data-bs-toggle="tooltip"
                                            title="Enrolled Students"
                                            class="btn btn-primary btn-sm">
                                            <i class="bi bi-people"></i>
                                        </a>
                                        <a href="manage_sec.php?secID=<?php echo $row['secID']?>&gradelvlID=<?php echo $row['gradelvlID']?>&ayID=<?php echo $row['ayID']?>&programID=<?php echo $row['programID']?>&semID=<?php echo $row['semID']?>&deptID=<?php echo $deptID?>" 
                                            data-bs-toggle="tooltip"
                                            title="Enrolled Students"
                                            class="btn btn-info btn-sm">
                                            <i class="bi bi-book"></i>
                                        </a>
                                        <button class="btn btn-secondary active-btn btn-sm me-1" 
                                            data-bs-toggle="tooltip"
                                            title="Move to Inactive"
                                            data-active-id="<?php echo $row['secID']; ?>" 
                                            data-active-program="<?php echo htmlspecialchars($row['programcode']); ?>">
                                            <i class="bi bi-recycle"></i>
                                        </button>
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

                                                      
       <?php require_once("modals/curriculumModal.php")?>  
    </main><!-- End #main -->
    

  <?php require_once"support/footer.php"?>            

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <script src="assets/sweetalert2.all.min.js"></script>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
 
  <script src="assets/js/main.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<script>
    function handleRetrieveButtonClick(archivedSecID, archivedSecName, program, rowElement) {
        Swal.fire({
            title: 'Confirmation Required',
            html: 'You are about to restore the section <strong>' + program + '</strong> <strong>' + archivedSecName + '</strong>.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Restore'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('inactivateSec.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'archivedSecID=' + encodeURIComponent(archivedSecID)
                })
                .then(response => {
                    // Check if the response is OK (status in the range 200-299)
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Restored Successfully!',
                            html: 'The section titled <strong>' + archivedSecName + '</strong> has been restored to the sections table.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            rowElement.remove();
                            setTimeout(function() {
                                location.reload(); 
                            }, 100);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Restoration Failed',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error during fetch:', error); // Log the error to the console
                    Swal.fire({
                        icon: 'error',
                        title: 'An Error Occurred',
                        text: 'There was a problem restoring the section. Please try again later. If the problem persists, contact support.'
                    });
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.active-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); 
                var program = this.getAttribute('data-active-program');
                var archivedSecID = this.getAttribute('data-active-id');
                var archivedSecName = this.closest('tr').querySelector('td:nth-child(5)').innerText; 
                var rowElement = this.closest('tr'); 
                handleRetrieveButtonClick(archivedSecID, archivedSecName, program, rowElement);
            });
        });
    });
</script>




</body>

</html>
