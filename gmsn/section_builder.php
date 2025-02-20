<?php
include 'session.php';
require_once "fetch/fetch_activeAY.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Section Builder</title>
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
    /* .nav-link.active {
        color: black !important;
        border-color: #003366 #003366 #ffffff !important; 
        font-weight: bold;
        font-size: 15px;
    } */
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
    table {
        width: 100%;
        table-layout: auto;
    }

    .table-responsive {
        overflow-x: auto;
    }

    @media (max-width: 768px) {
        td {
            font-size: 11px;
            height: 20px;
        }
    }
    .nav-link {
        color: rgb(110, 110, 110);
        font-weight: 500;
        font-size: 14px;
        }

        .nav-link:hover {
        color: white;
        background-color: darkblue;
        }

        .tab-content {
        padding-bottom: 1.3rem;
        }
    #myTab .nav-link.active {
        color: black !important;
        border-color: #003366 #003366 #ffffff !important;
        font-weight: bold;
        font-size: 15px;
    }
    .tab-content {
        padding-bottom: 1.3rem;
    }
  </style>

</head>

<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>

    <main id="main" class="main">
        <section class="section">
                <!-- THIS container is for shs -->
                <div class="custom-container">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Sections</li>
                        </ol>
                    </nav>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link me-1" id="elemSections-tab" data-bs-toggle="tab" href="#elemSections" role="tab" aria-controls="elemSections" aria-selected="false">Elementary</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link me-1" id="jhsSections-tab" data-bs-toggle="tab" href="#jhsSections" role="tab" aria-controls="jhsSections" aria-selected="false">Junior High School</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="shsSections-tab" data-bs-toggle="tab" href="#shsSections" role="tab" aria-controls="shsSections" aria-selected="false">Senior High School</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="deptTabContent">
                                    <!-- elementary sections -->
                                     <?php include "sections/elemsec.php"?>
                                    <!-- junior high sections -->
                                     <?php include "sections/jhssec.php"?>
                                    <!-- senior high table sections -->
                                    <?php include "sections/shssec.php"?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

       <?php require_once("modals/sectionsModal.php")?>                                                

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
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('#myTab .nav-link');
            const activeTab = localStorage.getItem('activeTab');

            // Set the default tab to "Elementary" if no tab is stored in local storage
            if (!activeTab) {
                const defaultTab = document.querySelector('#myTab a[href="#elemSections"]');
                if (defaultTab) {
                    new bootstrap.Tab(defaultTab).show();
                }
            } else {
                const tabToActivate = document.querySelector(`#myTab a[href="${activeTab}"]`);
                if (tabToActivate) {
                    new bootstrap.Tab(tabToActivate).show();
                }
            }

            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function () {
                    localStorage.setItem('activeTab', this.getAttribute('href'));
                });
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);

        // Get status and message from the URL
        const status = urlParams.get('status');
        const updStatus = urlParams.get('updstatus');
        const message = urlParams.get('message') || '';

        // Determine the alert type and content based on status
        if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Section Saved',
                text: 'The section has been successfully saved.',
                timer: 3000
            }).then(() => {
                // Clear URL parameters after alert
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (status === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message || 'An error occurred while saving the section.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear URL parameters after alert
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (updStatus === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Section Updated',
                text: 'The section has been successfully updated.'
            }).then(() => {
                // Clear URL parameters after alert
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (updStatus === 'duplication') {
            Swal.fire({
                icon: 'warning',
                title: 'Duplicate Section!',
                text: 'The section name already exists for the active session. Please check the entered information and try again.'
            }).then(() => {
                // Clear URL parameters after alert
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (status === 'duplicate') {
            Swal.fire({
                icon: 'warning',
                title: 'Duplicate Section!',
                text: 'The section name already exists for the active session. Please check the entered information and try again.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear URL parameters after alert
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        }else if (updStatus === 'no-changes') {
            Swal.fire({
                icon: 'info',
                title: 'Notice',
                text: message || 'No changes were made when updating section.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear URL parameters after alert
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (updStatus === 'empty') {
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: message || 'The Section Name field cannot be empty.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear URL parameters after alert
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        }
    });
</script>






</body>


</html>