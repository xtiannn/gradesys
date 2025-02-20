<?php 
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
  require_once("includes/config.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Enrolled Students</title>
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

</head>
<style>
    td{
        font-size: 14px;
    }
</style>
<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>

    <main id="main" class="main">
        <section class="section">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="section_builder.php">Sections</a></li>
                      <li class="breadcrumb-item active">Manage Students</li>
                    </ol>
                </nav>
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title"><?php 
                                $secName = isset($_GET['secName']) ? $_GET['secName'] : '';
                                echo $secName ?></h5>
                            </div>
                            <div class="col-md-2">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="file" name="import_file" id="import_file" class="form-control form-control-sm" style="display: none;" />
                                        <label for="import_file" class="btn btn-outline-success btn-sm">
                                            Import Excel
                                        </label>
                                        <button type="submit" name="save_excel_data" style="height: 31px" class="btn btn-success btn-sm"><i class="bi bi-upload"></i></button>
                                    </div>                            
                                </div>
                            </form>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#promoteStudentModal">
                                <i class="bi bi-arrow-right-circle"></i> Promote All
                                </a>
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentSec">
                                <i class="bi bi-person-add"></i> Enroll Student
                                </a>
                            </div>
                        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>LRN</th>
                                        <th>Name</th>
                                        <th class="text-center" style="width: 250px">Action</th>
                                    </tr>
                                </thead>
                                <?php 
                                    $curriculum = [];
                                    if(isset($_GET['programID'], $_GET['gradelvlID'], $_GET['semID'])) {
                                    $programID = $_GET['programID'];
                                    $gradelvlID = $_GET['gradelvlID'];
                                    $semID = $_GET['semID'];
                                    $secID = $_GET['secID'];

                                    require_once("includes/config.php");

                                    $query = 
                                    "SELECT ss.*, (SELECT lrn FROM students s WHERE ss.studID = s.studID) as lrn,
                                    (SELECT CONCAT(lname, ', ', fname, ' ', mname) FROM students s WHERE ss.studID = s.studID) as studname
                                    FROM section_students ss WHERE ss.semID = :semID AND ss.gradelvlID = :gradelvlID AND secID = :secID";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                    $count = 0;
                                    ?>
                                <tbody>
                                    <?php foreach ($curriculum as $row): 
                                         if (!in_array($row['studID'], $curriculum)):
                                            // If not printed, add it to the printedStudIDs array
                                            $curriculum[] = $row['studID'];
                                    ?>
                                        <tr>
                                            <td><?php echo ++$count; ?></td> 
                                            <td><?php echo ($row['lrn']); ?></td>
                                            <td><?php echo ($row['studname']); ?></td>
                                            <td class="text-center">
                                                <!-- <button class="btn btn-info btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#enrollSubjects">
                                                    <i class="bi bi-gear"></i> Subjects
                                                </button> -->
                                                <a class="btn btn-primary btn-sm" type="button" href="students_subj.php?studID=<?php echo $row['studID']; ?>&semID=<?php echo $row['semID']; ?>&secID=<?php echo $row['secID']; ?>&gradelvlID=<?php echo $row['gradelvlID']; ?>&programID=<?php echo $row['programID']; ?>&studName=<?php echo $row['studname']; ?>
                                                &subjectID=0&secName=<?php echo $secName ?>&ayID=<?php echo $row['ayID'] ?>">
                                                    <i class="bi bi-gear"></i> Subjects
                                                </a>
                                                <button class="btn btn-danger delete-btn btn-sm"  data-enroll-id="<?php echo $row['enrollID']; ?>" data-stud-id="<?php echo $row['studID']; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php 
                                    endif;
                                    endforeach; 
                                    ?>
                                </tbody>
                                <?php 
                                    } 
                                    else {
                                        echo "Not all required parameters are set!";
                                        }
                                ?>
                            </table>
                                </div>
                            </div>
                    </div>
            </div>
        </section>

       <?php include"modals/studentM.php"?>                                                

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
function handleDeleteButtonClick(enrollID, studName, studID) {
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
                url: 'delete_student.php',
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
                        text: 'Failed to delete student. Please try again later.'
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
            var studName = this.closest('tr').querySelector('td:nth-child(3)').innerText; 
            handleDeleteButtonClick(enrollID, studName, studID);
        });
    });
});
</script>
</body>


</html>