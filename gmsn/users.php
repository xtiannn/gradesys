<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>User Accounts Management</title>
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

    <main id="main" class="main mt-0">
        <section class="section">
            <div class="custom-container">
                <div class="col-lg-12">
                    <?php if (isset($_GET['stat']) && $_GET['stat'] === 'inactive') : ?>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item" aria-current="page"><a href="users.php">Active Users</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Inactive Users</li>
                                </ol>
                            </nav>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <h6 class="custom-card-title">
                                    <i class="bi bi-person-fill-slash me-2"></i></i> Inactive Users
                                    </h6>
                                </div>
                                <div class="d-flex align-items-center mb-0">
                                    <a href="userCreate.php" type="button" class="btn btn-primary btn-sm" >
                                    <i class="bi bi-person-plus-fill"></i>
                                        Create User
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Table with stripped rows -->
                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                        <?php 
                                            require_once "includes/config.php";

                                            $query = "
                                                SELECT s.*, ut.userType 
                                                FROM users s
                                                JOIN user_type ut ON s.userTypeID = ut.typeID
                                                WHERE s.isActive = 0
                                                ";
                                                                           
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                            $count = 0;
                                        ?>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">User Type</th>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($faculties as $faculty): ?>
                                            <tr>
                                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                                <td class="text-center"><?php echo htmlspecialchars($faculty['userType']); ?></td>
                                                <td><?php echo htmlspecialchars($faculty['userID']); ?></td>
                                                <td><?php echo htmlspecialchars(ucwords(strtolower($faculty['lname'])).', '.ucwords(strtolower($faculty['fname'])).' '.ucwords(strtolower($faculty['mname']))); ?></td>
                                                <td><?php echo htmlspecialchars(strtolower($faculty['email'])); ?></td>
                                                <td>
                                                    <div class="text-center">
                                                        <!-- <a style="width: 50px" class="btn btn-primary btn-sm update-btn" href="userCreate.php?userID=<?php echo $faculty['uid']; ?>">
                                                            <i class="bi bi-pencil-square"></i> 
                                                        </a> -->
                                                        <button style="width: 50px;" class="btn btn-primary retrieve-btn btn-sm" 
                                                            data-user-id="<?php echo $faculty['uid']; ?>"
                                                            data-bs-toggle="tooltip"
                                                            title="Activate User"
                                                            >
                                                            <i class="bi bi-person-check fs-6"></i> 
                                                            </button>
                                                        <!-- <button style="width: 50px;" class="btn btn-danger delete-btn btn-sm" 
                                                            data-user-id="<?php echo $faculty['uid']; ?>"
                                                            data-bs-toggle="tooltip"
                                                            title="Delete User"
                                                            <?php echo ($faculty['userID'] == $_SESSION['userID']) ? 'disabled' : ''; ?>>
                                                            <i class="bi bi-trash"></i> 
                                                        </button> -->

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function () {
                                                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                                                tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                                                                    new bootstrap.Tooltip(tooltipTriggerEl);
                                                                });
                                                            });
                                                        </script>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- End Table with stripped rows -->
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <h6 class="custom-card-title">
                                    <i class="bi bi-person-fill me-2"></i> User Accounts Management
                                    </h6>
                                </div>
                                <div class="d-flex align-items-center mb-0">
                                    <!-- <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUser">
                                        <i class="bi bi-person-plus-fill"></i>
                                        Create User
                                    </button> -->
                                    <a href="users.php?stat=inactive" type="button" class="btn btn-secondary btn-sm me-1" >
                                    <i class="bi bi-archive"></i>
                                        Inactive User
                                    </a>
                                    <a href="userCreate.php" type="button" class="btn btn-primary btn-sm" >
                                        <i class="bi bi-person-plus-fill"></i>
                                        Create User
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Table with stripped rows -->
                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                        <?php 
                                            require_once "includes/config.php";

                                            $loggedInUserID = $_SESSION['userID'];

                                            $query = "
                                                (SELECT s.*, (SELECT userType FROM user_type WHERE typeID = s.userTypeID) AS userType
                                                FROM users s
                                                WHERE s.userID = :loggedInUserID AND isActive = 1)
                                                UNION ALL
                                                (SELECT s.*, (SELECT userType FROM user_type WHERE typeID = s.userTypeID) AS userType
                                                FROM users s
                                                WHERE s.userID != :loggedInUserID AND isActive = 1)
                                                ORDER BY CASE WHEN userID = :loggedInUserID THEN 0 ELSE 1 END, userType ASC, lname ASC
                                            ";                                       
                                            $stmt = $conn->prepare($query);
                                            $stmt->bindParam(':loggedInUserID', $loggedInUserID);
                                            $stmt->execute();
                                            $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                            $count = 0;
                                        ?>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">User Type</th>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($faculties as $faculty): ?>
                                            <tr>
                                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                                <td class="text-center"><?php echo htmlspecialchars($faculty['userType']); ?></td>
                                                <td><?php echo htmlspecialchars($faculty['userID']); ?></td>
                                                <td><?php echo htmlspecialchars(ucwords(strtolower($faculty['lname'])).', '.ucwords(strtolower($faculty['fname'])).' '.ucwords(strtolower($faculty['mname']))); ?></td>
                                                <td><?php echo htmlspecialchars($faculty['email']); ?></td>
                                                <td>
                                                    <div class="text-center">
                                                        <a style="width: 50px" class="btn btn-primary btn-sm update-btn" href="userUpdate.php?userID=<?php echo $faculty['uid']; ?>&uTypeID=<?php echo $_SESSION['userTypeID']?>">
                                                            <i class="bi bi-pencil-square"></i> 
                                                        </a>
                                                        <!-- <a style="width: 50px" class="btn btn-primary btn-sm update-btn" href="#" data-bs-toggle="modal" data-bs-target="#updateFacultyModal" 
                                                        data-faculty-id="<?php //echo $faculty['id']; ?>"
                                                        data-faculty-lname="<?php //echo $faculty['lname']; ?>"
                                                        data-faculty-fname="<?php //echo $faculty['fname']; ?>"
                                                        data-faculty-mname="<?php //echo $faculty['mname']; ?>"
                                                        data-faculty-gender="<?php //echo $faculty['gender']; ?>"
                                                        data-faculty-contact="<?php //echo $faculty['contact']; ?>"
                                                        data-faculty-email="<?php //echo $faculty['email']; ?>"
                                                        data-faculty-password="<?php //echo $faculty['password']; ?>"
                                                        data-faculty-userID="<?php //echo $faculty['userID']; ?>">
                                                            <i class="bi bi-pencil-square"></i> 
                                                        </a> -->
                                                        <button style="width: 50px;" class="btn btn-danger delete-btn btn-sm" 
                                                            data-user-id="<?php echo $faculty['uid']; ?>"
                                                            data-bs-toggle="tooltip"
                                                            title="Inactivate User"
                                                            <?php echo ($faculty['userID'] == $_SESSION['userID']) ? 'disabled' : ''; ?>
                                                        >
                                                        <i class="bi bi-person-fill-slash"></i>
                                                        </button>

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function () {
                                                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                                                tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                                                                    new bootstrap.Tooltip(tooltipTriggerEl);
                                                                });
                                                            });
                                                        </script>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- End Table with stripped rows -->
                            </div>
                        </div>
                    <?php endif?>
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
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

        if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                showConfirmButton: true,
                timer: 5000,
                text: 'User account has been created successfully.'
            });
        } else if (status === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'There was a problem creating the user. Please try again.'
            });
        } else if (status === 'password_error') {
            Swal.fire({
                icon: 'warning',
                title: 'Password Error',
                text: 'Passwords do not match. Please try again.'
            });
        } else if (status === 'duplicate_userID') {
            Swal.fire({
                icon: 'error',
                title: 'User ID Already Taken',
                text: 'The User ID you entered is already in use. Please choose a different User ID and try again.'
            });
        }
    });
function removeUrlParameter(key) {
    if (history.replaceState) {
        var url = window.location.href;
        var cleanedUrl = url.replace(new RegExp('[?&]' + key + '=[^&#]*(#.*)?$'), '$1').replace(/[?&]$/, '');
        history.replaceState({}, document.title, cleanedUrl);
    }
}

window.addEventListener('load', function() {
    removeUrlParameter('status');
});
//for update
    document.addEventListener('DOMContentLoaded', function() {
    const urlParamss = new URLSearchParams(window.location.search);
    const updstatus = urlParamss.get('updstatus');

        if (updstatus === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                showConfirmButton: true,
                timer: 5000,
                text: 'User account has been updated successfully.'
            });
        } else if (updstatus === 'password_error') {
            Swal.fire({
                icon: 'warning',
                title: 'Password Error',
                text: 'Passwords do not match. Please try again.'
            });
        }else if (updstatus === 'missing_fields') {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Form',
                text: 'Please ensure that all required fields are filled out before submitting the form.'
            });
        }else if (updstatus === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'User ID Already Taken',
                text: 'The User ID you entered is already in use. Please choose a different User ID and try again.'
            });
        }
    });
function removeUrlParameter(key) {
    if (history.replaceState) {
        var urls = window.location.href;
        var cleanedUrls = urls.replace(new RegExp('[?&]' + key + '=[^&#]*(#.*)?$'), '$1').replace(/[?&]$/, '');
        history.replaceState({}, document.title, cleanedUrls);
    }
}

window.addEventListener('load', function() {
    removeUrlParameter('updstatus');
});

</script>
<script>
    function handleDeleteButtonClick(uid, facultyName) {
        Swal.fire({
            title: 'Confirmation Required!',
            text: 'You are about to inactivate the User: ' + facultyName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Inactivate'
        }).then((result) => {
            if (result.isConfirmed) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_faculty.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Inactivated!',
                                    text: 'The user has been inactivated successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload(); // Reload the page
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Deletion Failed',
                                    text: response.message
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to inactivate the user. Please try again later.'
                            });
                        }
                    }
                };
                xhr.send('uid=' + uid); 
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); 
                var uid = this.getAttribute('data-user-id');
                var facultyName = this.closest('tr').querySelector('td:nth-child(3)').innerText; 
                handleDeleteButtonClick(uid, facultyName);
            });
        });
    });
</script>
<script>
    function handleRetrieveButtonClick(uid, facultyName) {
        Swal.fire({
            title: 'Confirmation Required!',
            text: 'You are about to activate the User: ' + facultyName,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Activate'
        }).then((result) => {
            if (result.isConfirmed) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'retrieve_faculty.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Activated!',
                                    text: 'The user has been activated successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload(); // Reload the page
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Activation Failed',
                                    text: response.message
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to activate the user. Please try again later.'
                            });
                        }
                    }
                };
                xhr.send('uid=' + uid); 
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.retrieve-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); 
                var uid = this.getAttribute('data-user-id');
                var facultyName = this.closest('tr').querySelector('td:nth-child(3)').innerText; 
                handleRetrieveButtonClick(uid, facultyName);
            });
        });
    });
</script>


</body>

</html>