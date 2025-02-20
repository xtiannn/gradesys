<?php
include 'session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Curriculum Management</title>
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
                            <li class="breadcrumb-item" aria-current="page"><a href="curri.php">Programs</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Curriculum</li>
                        </ol>
                    </nav>
                        <div class="row">
                        <?php
                        require_once("includes/config.php");
                        if(isset($_GET['program_id'])) {
                            $programID = $_GET['program_id'];
                            $query = "SELECT programcode FROM programs WHERE programID = :programID";
                            $stmt = $conn->prepare($query);
                            $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
                            $stmt->execute();
                            $program = $stmt->fetch(PDO::FETCH_ASSOC); 
                        }
                        ?>
                        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <h6 class="custom-card-title">
                                    <i class="bi bi-book me-2"></i>
                                        <?php echo $program['programcode']?> 
                                        Curriculum Management
                                    </h6>
                                </div>
                                <div class="d-flex align-items-center mb-0">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCurr">
                                        <i class="bi bi-plus"></i>
                                        Add Subject
                                    </button>  
                                </div>
                            </div>
                            <div class="card-body">
                                    <table class="table table-striped table-bordered datatable">
                                        <thead>
                                            <?php
                                            require_once("includes/config.php");
                                            if (isset($_GET['program_id'])) {
                                                $programID = $_GET['program_id'];
                                                $deptID = $_GET['deptID'];
                                            
                                                $query = "SELECT c.*, sb.subjectname, sb.subjectcode,
                                                GROUP_CONCAT(pr.gradelvlID) AS gradelvlIDs,
                                                GROUP_CONCAT(gl.gradelvlcode) AS gradelvlCodes,
                                                (SELECT p.programcode FROM programs p WHERE c.programID = p.programID) AS programname
                                                FROM curriculum c
                                                JOIN subjects sb ON c.subjectID = sb.subjectID
                                                JOIN subject_grade_levels pr ON c.curriculumID = pr.curriculumID
                                                JOIN grade_level gl ON pr.gradelvlID = gl.gradelvlID
                                                WHERE c.programID = :programID
                                                GROUP BY c.curriculumID
                                                ORDER BY sb.subjectname ASC, c.gradelvlID ASC";
                                        
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
                                                $stmt->execute();
                                                $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $count = 0;
                                            } else {
                                            
                                                echo "Program ID not provided in URL.";
                                            }
                                            ?>

                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>Grade Level</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($programs as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?php echo ++$count; ?>.</td> 
                                                    <td><?php echo htmlspecialchars($row['subjectcode']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['subjectname']); ?></td>
                                                    <td>
                                                        <?php 
                                                        // Combine IDs and codes into an associative array
                                                        $gradelvlIDs = explode(",", $row['gradelvlIDs']);
                                                        $gradelvlCodes = explode(",", $row['gradelvlCodes']);
                                                        $gradeLevels = array();

                                                        for ($i = 0; $i < count($gradelvlIDs); $i++) {
                                                            $gradeLevels[$gradelvlIDs[$i]] = $gradelvlCodes[$i];
                                                        }

                                                        // Sort the array by keys (gradelvlIDs)
                                                        ksort($gradeLevels);

                                                        // Output sorted codes
                                                        if (!empty($gradeLevels)) { // Ensure this check is correct
                                                            $output = array();
                                                            foreach ($gradeLevels as $code) {
                                                                $output[] = htmlspecialchars($code); // Safely output the grade level codes
                                                            }
                                                            echo implode(', ', $output);
                                                        } else {
                                                            echo 'Not defined'; 
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center" style="width: 90px">
                                                    <button class="btn btn-primary updateCurr-btn btn-sm" 
                                                    data-curri-id="<?php echo $row['curriculumID']; ?>" 
                                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                                    data-subject-name="<?php echo $row['subjectname']; ?>"
                                                    data-gradelvl-ids="<?php echo $row['gradelvlIDs']; ?>">                                                        
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-danger delete-btn btn-sm" 
                                                            data-curri-id="<?php echo $row['curriculumID']; ?>" 
                                                            data-curri-prereq="<?php echo $row['programname']; ?>" 
                                                            data-dept-id="<?php echo $deptID; ?>"> 
                                                        <i class="bi bi-trash"></i> 
                                                    </button>
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

                                                      
       <?php require_once("modals/curriculumModalJSH.php")?>  
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
    function handleDeleteButtonClick(subjectId, subjectName, rowElement, deptID) {
    Swal.fire({
        title: 'Confirmation Required!',
        text: 'You are about to delete the subject: ' + subjectName + ' from the curriculum.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_curriculum.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'The subject has been deleted successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // Remove the deleted row from the table
                                rowElement.remove();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to delete the subject: ' + response.message
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Failed to delete the subject. Please try again later.'
                        });
                    }
                }
            };
            xhr.send('curriculumID=' + encodeURIComponent(subjectId) + '&deptID=' + encodeURIComponent(deptID));
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            var subjectId = this.getAttribute('data-curri-id');
            var subjectName = this.closest('tr').querySelector('td:nth-child(3)').innerText;
            var deptID = this.getAttribute('data-dept-id'); // Ensure deptID is included
            var rowElement = this.closest('tr'); // Get the row element to remove it later
            handleDeleteButtonClick(subjectId, subjectName, rowElement, deptID);
        });
    });
});

</script>



</body>

</html>
