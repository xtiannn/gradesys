<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Subjects</title>
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
    td{
        font-size: 14px;
    }
    th{
        font-size: 15px;
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
        <section class="section">
            <div class="custom-container">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <h6 class="custom-card-title">
                                    <i class="bi bi-book me-2"></i> Subject Management
                                    </h6>
                                </div>
                                <div class="d-flex align-items-center mb-0">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                        <i class="bi bi-journal-plus"></i>
                                        Add Subject
                                    </button> 
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-bordered datatable" id="datatable">
                                    <thead>
                                    <?php 
                                    
                                    require_once("includes/config.php");
                                    function getProgramName($programID, $conn) {
                                        $query = "SELECT programcode FROM programs WHERE programID = ?";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute([$programID]);
                                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                        return $result ? $result['programcode'] : 'Unknown Program';
                                    }
                                    $query = "
                                        SELECT s.*, GROUP_CONCAT(sp.programID) as programIDs
                                        FROM subjects s 
                                        LEFT JOIN subject_program sp ON s.subjectID = sp.subjectID
                                        GROUP BY s.subjectID
                                        ORDER BY s.isActive DESC, s.subjectname";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $count = 0;
                                    ?>

                                        <tr>                                         
                                            <th class="text-center">#</th>
                                            <th>Subject Code</th>
                                            <th>Subject Description</th>
                                            <th class="text-center">Associated Program</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center" style="width: 150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($subjects as $subject): ?>
                                        <tr>
                                            <td class="text-center"><?php echo ++$count; ?>.</td> 
                                            <td class="subject-code"><?php echo htmlspecialchars($subject['subjectcode']); ?></td>
                                            <td class="subject-name"><?php echo htmlspecialchars($subject['subjectname']); ?></td>
                                            <td class="subject-program" style="white-space: nowrap">
                                                <?php
                                                    $programIDs = explode(',', $subject['programIDs']);
                                                    $programNames = array_map(function($programID) use ($conn) {
                                                        return htmlspecialchars(getProgramName($programID, $conn));
                                                    }, $programIDs);

                                                    // Sort program names alphabetically
                                                    sort($programNames, SORT_STRING);

                                                    echo implode(', ', $programNames);
                                                ?>
                                            </td>
                                            <td class="subject-name text-center">
                                            <?php
                                                if ($subject['isActive'] == 1) {
                                                    echo '<span class="badge bg-success">Active</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning">Inactive</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <button style="width: 50px;" id="update-btn" class="btn btn-primary update-btn btn-sm" data-bs-toggle="modal" data-bs-target="#updateSubjectModal" 
                                                    data-subject-id="<?php echo $subject['subjectID']; ?>"
                                                    data-subject-name="<?php echo htmlspecialchars($subject['subjectname']); ?>" 
                                                    data-subject-code="<?php echo htmlspecialchars($subject['subjectcode']); ?>" 
                                                    data-program-status="<?php echo htmlspecialchars($subject['isActive']); ?>" 
                                                    data-subject-program="<?php echo htmlspecialchars($subject['programIDs']); ?>">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <!-- <button style="width: 50px" class="btn btn-danger delete-btn btn-sm" data-subject-id="<?php //echo $subject['subjectID']; ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button> -->
                                                </div>
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
    document.addEventListener('DOMContentLoaded', function() {
    function handleDeleteButtonClick(subjectId, subjectName) {
        Swal.fire({
            title: 'Confirmation Required',
            text: 'Please confirm your action: You are about to delete the subject "' + subjectName + '".',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_subject.php', true);
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
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Deletion Failed!',
                                    text: response.message
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to delete the subject. Please try again later.'
                            });
                        }
                    }
                };
                xhr.send('subjectId=' + encodeURIComponent(subjectId));
            }
        });
    }

    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            var subjectId = this.getAttribute('data-subject-id');
            var subjectName = this.closest('tr').querySelector('td:nth-child(3)').innerText;
            handleDeleteButtonClick(subjectId, subjectName);
        });
    });

    updateButtonsState();
});

</script>

<?php include"modals/subjectsModal.php"?>   

</body>

</html>