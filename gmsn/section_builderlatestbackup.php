<?php
include 'session.php';
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
    /* Custom style for active tab */
    .nav-link.active {
        color: black !important;
        border-color: #003366 #003366 #ffffff !important; /* Dark blue border on top and sides, white on bottom */
    }
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
  color: #1a237e;
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
                                        <a class="nav-link" id="elemSections-tab" data-bs-toggle="tab" href="#elemSections" role="tab" aria-controls="elemSections" aria-selected="false">Elementary</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="jhsSections-tab" data-bs-toggle="tab" href="#jhsSections" role="tab" aria-controls="jhsSections" aria-selected="false">Junior High School</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="shsSections-tab" data-bs-toggle="tab" href="#shsSections" role="tab" aria-controls="shsSections" aria-selected="false">Senior High School</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="deptTabContent">
                                    <!-- elementary sections -->
                                    <div class="tab-pane fade" id="elemSections" role="tabpanel" aria-labelledby="elemSections-tab">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <h6 class="custom-card-title">
                                                <i class="bi bi-folder me-2"></i>
                                                Sections Management
                                                </h6>
                                            </div>
                                            <div class="d-flex align-items-center mb-0">
                                                <button type="button" class="btn btn-primary btn-sm" id="btnAdd" data-bs-toggle="modal" data-bs-target="#addSectionModalElem">
                                                <i class="bi bi-plus-lg"></i>
                                                    Create Section
                                                </button> 
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped datatable">
                                                <thead>
                                                    <?php 
                                                        require_once("includes/config.php");
                                                        $query = "SELECT s.*, gl.*, ay.ayName,
                                                        (SELECT lname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_lname,
                                                        (SELECT fname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_fname,
                                                        (SELECT gender FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_gender
                                                    FROM sections s
                                                    JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
                                                    JOIN academic_year ay ON s.ayID = ay.ayID
                                                    WHERE s.isActive = 1 AND gl.deptID = 1
                                                    ORDER BY s.secID DESC";
                                        
                                                        $stmt = $conn->prepare($query);
                                                        $stmt->execute();
                                                        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);  

                                                        $count = 0;
                                                    ?>
                                                    <tr>
                                                        <th style="width: 5%;" class="text-center">#</th>
                                                        <th style="width: 10%;" class="text-center">A.Y.</th>
                                                        <th style="width: 30%;">Section</th>
                                                        <th style="width: 20%;">Adviser</th>
                                                        <th style="width: 20%;" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        foreach ($sections as $row): 
                                                            $lname = htmlspecialchars($row['adviser_lname']);
                                                            $fname = htmlspecialchars($row['adviser_fname']);
                                                            $gender = $row['adviser_gender'];

                                                            $initials = strtoupper(substr($fname, 0, 1)) . '.';
                                

                                                            $prefix = ($gender === 'Female') ? 'Ms. ' : 'Mr. ';
                                                            $formattedAdviser = $prefix . ' ' . $lname . ' ' . $initials;
                                                    ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo ++$count; ?>.</td> 
                                                            <td class="text-center"><?php echo htmlspecialchars($row['ayName']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['gradelvlcode'].' - '.$row['secName']); ?></td>
                                                            <td><?php echo $formattedAdviser; ?></td>
                                                            <td class="text-center">
                                                                <div class="d-flex justify-content-center align-items-center">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-info btn-sm dropdown-toggle" style="font-size: 12px; margin-right: 5px; height: 31px" type="button" id="manageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i style="font-size: 14px" class="bi bi-gear"></i> Manage
                                                                        </button>
                                                                        <ul class="dropdown-menu" aria-labelledby="manageDropdown">
                                                                            <li>
                                                                                <a href="manage_sec.php?secName=<?php echo $row['gradelvl'].' - '.$row['secName'] ?>
                                                                                &secID=<?php echo $row['secID']?>
                                                                                &gradelvlID=<?php echo $row['gradelvlID']; ?>
                                                                                &ayID=<?php echo $row['ayID']; ?>
                                                                                &deptID=1" 
                                                                                class="dropdown-item">
                                                                                    <i class="bi bi-book"></i> Subjects
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="enrolled_students.php?secName=<?php echo $row['gradelvl'].' - '.$row['secName'] ?>&secID=<?php echo $row['secID']?>&gradelvlID=<?php echo $row['gradelvlID']; ?>&ayID=<?php echo $row['ayID']; ?>&facultyID=<?php echo $row['facultyID']; ?>&deptID=1" class="dropdown-item">
                                                                                    <i class="bi bi-people"></i> Students
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <a class="btn btn-primary update-btn-elem btn-sm me-1" href="#" data-bs-toggle="modal" data-bs-target="#updateSectionModalElem" 
                                                                        data-section-id="<?php echo $row['secID']; ?>"
                                                                        data-section-name="<?php echo $row['secName']; ?>"
                                                                        data-section-ay="<?php echo $row['ayID']; ?>"
                                                                        data-section-gradelvl="<?php echo $row['gradelvlID']; ?>"
                                                                        data-faculty-id="<?php echo $row['facultyID'];?>">
                                                                        <i class="bi bi-pencil-square"></i>
                                                                    </a>
                                                                    <button class="btn btn-danger delete-btn btn-sm" data-section-id="<?php echo $row['secID']; ?>">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- junior high sections -->
                                    <div class="tab-pane fade" id="jhsSections" role="tabpanel" aria-labelledby="jhsSections-tab">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <h6 class="custom-card-title">
                                                <i class="bi bi-folder me-2"></i>
                                                Sections Management
                                                </h6>
                                            </div>
                                            <div class="d-flex align-items-center mb-0">
                                                <button type="button" class="btn btn-primary btn-sm" id="btnAdd" data-bs-toggle="modal" data-bs-target="#addSectionModalJHS">
                                                <i class="bi bi-plus-lg"></i>
                                                    Create Section
                                                </button> 
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped datatable">
                                                <thead>
                                                    <?php 
                                                        require_once("includes/config.php");
                                                        $query = "SELECT s.*, gl.*, ay.ayName,
                                                        (SELECT lname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_lname,
                                                        (SELECT fname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_fname,
                                                        (SELECT gender FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_gender
                                                    FROM sections s
                                                    JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
                                                    JOIN academic_year ay ON s.ayID = ay.ayID
                                                    WHERE s.isActive = 1 AND gl.deptID = 2
                                                    ORDER BY s.secID DESC";
                                        
                                                        $stmt = $conn->prepare($query);
                                                        $stmt->execute();
                                                        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);  

                                                        $count = 0;
                                                    ?>
                                                    <tr>
                                                        <th style="width: 5%;" class="text-center">#</th>
                                                        <th style="width: 10%;" class="text-center">A.Y.</th>
                                                        <th style="width: 30%;">Section</th>
                                                        <th style="width: 20%;">Adviser</th>
                                                        <th style="width: 20%;" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        foreach ($sections as $row): 
                                                            $lname = htmlspecialchars($row['adviser_lname']);
                                                            $fname = htmlspecialchars($row['adviser_fname']);
                                                            $gender = $row['adviser_gender'];

                                                            $initials = strtoupper(substr($fname, 0, 1)) . '.';
                                

                                                            $prefix = ($gender === 'Female') ? 'Ms. ' : 'Mr. ';
                                                            $formattedAdviser = $prefix . ' ' . $lname . ' ' . $initials;
                                                    ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo ++$count; ?>.</td> 
                                                            <td class="text-center"><?php echo htmlspecialchars($row['ayName']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['gradelvlcode'].' - '.$row['secName']); ?></td>
                                                            <td><?php echo $formattedAdviser; ?></td>
                                                            <td class="text-center">
                                                                <div class="d-flex justify-content-center align-items-center">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-info btn-sm dropdown-toggle" style="font-size: 12px; margin-right: 5px; height: 31px" type="button" id="manageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i style="font-size: 14px" class="bi bi-gear"></i> Manage
                                                                        </button>
                                                                        <ul class="dropdown-menu" aria-labelledby="manageDropdown">
                                                                            <li>
                                                                                <a href="manage_sec.php?secName=<?php echo $row['gradelvl'].' - '.$row['secName'] ?>
                                                                                &secID=<?php echo $row['secID']?>
                                                                                &gradelvlID=<?php echo $row['gradelvlID']; ?>
                                                                                &ayID=<?php echo $row['ayID']; ?>
                                                                                &deptID=<?php echo $row['deptID']; ?>" 
                                                                                class="dropdown-item">
                                                                                    <i class="bi bi-book"></i> Subjects
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                            <a href="enrolled_students.php?
                                                                                secName=<?php echo urlencode(trim($row['gradelvl'] . ' - ' . $row['secName'])); ?>
                                                                                &secID=<?php echo urlencode(trim($row['secID'])); ?>
                                                                                &gradelvlID=<?php echo urlencode(trim($row['gradelvlID'])); ?>
                                                                                &ayID=<?php echo urlencode(trim($row['ayID'])); ?>
                                                                                &facultyID=<?php echo urlencode(trim($row['facultyID'])); ?>
                                                                                &deptID=2" 
                                                                                class="dropdown-item">
                                                                                <i class="bi bi-people"></i> Students
                                                                            </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <a class="btn btn-primary update-btn-jhs btn-sm me-1" href="#" data-bs-toggle="modal" data-bs-target="#updateSectionModalJHS" 
                                                                        data-section-id="<?php echo $row['secID']; ?>"
                                                                        data-section-name="<?php echo $row['secName']; ?>"
                                                                        data-section-ay="<?php echo $row['ayID']; ?>"
                                                                        data-section-gradelvl="<?php echo $row['gradelvlID']; ?>"
                                                                        data-faculty-id="<?php echo $row['facultyID'];?>">
                                                                        <i class="bi bi-pencil-square"></i>
                                                                    </a>
                                                                    <button class="btn btn-danger delete-btn btn-sm" data-section-id="<?php echo $row['secID']; ?>">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- senior high table sections -->
                                    <div class="tab-pane fade" id="shsSections" role="tabpanel" aria-labelledby="shsSections-tab">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <h6 class="custom-card-title">
                                                <i class="bi bi-folder me-2"></i>
                                                Sections Management
                                                </h6>
                                            </div>
                                            <div class="d-flex align-items-center mb-0">
                                                <button type="button" class="btn btn-primary btn-sm" id="btnAdd" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                                                <i class="bi bi-plus-lg"></i>
                                                    Create Section
                                                </button> 
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped datatable">
                                                <thead>
                                                    <?php 
                                                    require_once("includes/config.php");
                                                    $query = "SELECT s.*, gl.*, ay.ayName, sm.*, p.*,
                                                        (SELECT lname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_lname,
                                                        (SELECT fname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_fname,
                                                        (SELECT gender FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_gender
                                                    FROM sections s
                                                    JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
                                                    JOIN programs p ON s.programID = p.programID
                                                    JOIN academic_year ay ON s.ayID = ay.ayID
                                                    JOIN semester sm ON s.semID = sm.semID
                                                    WHERE s.isActive = 1 AND gl.deptID = 3
                                                    ORDER BY s.secID DESC";
                                                    
                                                    $stmt = $conn->prepare($query);
                                                    $stmt->execute();
                                                    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    
                                                    $count = 0;
                                                    ?>
                                                    <tr>
                                                        <th style="width: 20px" class="text-center">#</th>
                                                        <th style="width: 100px" class="text-center">A.Y.</th>
                                                        <th style="width: 100px" class="text-center">Term</th>
                                                        <th style="width: 100px" class="text-center">Program</th>
                                                        <th>Section</th>
                                                        <th>Adviser</th>
                                                        <th style="width: 100px" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($sections as $row): 
                                                        $lname = htmlspecialchars($row['adviser_lname']);
                                                        $fname = htmlspecialchars($row['adviser_fname']);
                                                        $gender = htmlspecialchars($row['adviser_gender']); // Ensure to escape data
                                                        
                                                        $initials = strtoupper(substr($fname, 0, 1)) . '.';
                                                        $prefix = ($gender === 'Female') ? 'Ms. ' : 'Mr. ';
                                                        $formattedAdviser = $prefix . ' ' . $lname . ' ' . $initials;
                                                    ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo ++$count; ?>.</td>
                                                            <td class="text-center"><?php echo htmlspecialchars($row['ayName']); ?></td>
                                                            <td class="text-center"><?php echo htmlspecialchars($row['semCode']); ?></td>
                                                            <td class="text-center"><?php echo htmlspecialchars($row['programcode']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['gradelvlcode'] . ' - ' . $row['secName']); ?></td>
                                                            <td><?php echo $formattedAdviser; ?></td>
                                                            <td class="text-center">
                                                                <div class="d-flex align-items-center justify-content-center">                                                            
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-info btn-sm dropdown-toggle" style="font-size: 12px; margin-right: 5px; height: 31px" type="button" id="manageDropdown<?php echo $row['secID']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i style="font-size: 14px" class="bi bi-gear"></i> Manage
                                                                        </button>
                                                                        <ul class="dropdown-menu" aria-labelledby="manageDropdown<?php echo $row['secID']; ?>">
                                                                            <li>
                                                                                <a href="manage_sec.php?secName=<?php echo urlencode($row['programcode'].' '.$row['gradelvl'].' - '.$row['secName']); ?>&programID=<?php echo urlencode($row['programID']); ?>&secID=<?php echo urlencode($row['secID']); ?>&gradelvlID=<?php echo urlencode($row['gradelvlID']); ?>&semID=<?php echo urlencode($row['semID']); ?>&ayID=<?php echo urlencode($row['ayID']); ?>&deptID=<?php echo urlencode($deptID); ?>" class="dropdown-item">
                                                                                    <i class="bi bi-book"></i> Subjects
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                            <a href="enrolled_students.php?
                                                                                semID=<?php echo urlencode(trim($row['semID'])); ?>
                                                                                &secName=<?php echo urlencode(trim($row['programcode'] . ' ' . $row['gradelvl'] . ' - ' . $row['secName'])); ?>
                                                                                &programID=<?php echo urlencode(trim($row['programID'])); ?>
                                                                                &secID=<?php echo urlencode(trim($row['secID'])); ?>
                                                                                &gradelvlID=<?php echo urlencode(trim($row['gradelvlID'])); ?>
                                                                                &ayID=<?php echo urlencode(trim($row['ayID'])); ?>
                                                                                &facultyID=<?php echo urlencode(trim($row['facultyID'])); ?>
                                                                                &deptID=3" 
                                                                                class="dropdown-item">
                                                                                <i class="bi bi-people"></i> Students
                                                                            </a>

                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <a class="btn btn-primary update-btn-shs btn-sm me-1" href="#" data-bs-toggle="modal" data-bs-target="#updateSectionModal"
                                                                        data-section-id="<?php echo $row['secID']; ?>"
                                                                        data-section-name="<?php echo htmlspecialchars($row['secName']); ?>"
                                                                        data-section-ay="<?php echo htmlspecialchars($row['ayID']); ?>"
                                                                        data-section-program="<?php echo htmlspecialchars($row['programID']); ?>"
                                                                        data-section-sem="<?php echo htmlspecialchars($row['semID']); ?>"
                                                                        data-section-adviser="<?php echo htmlspecialchars($row['facultyID']); ?>"
                                                                        data-section-gradelvl="<?php echo htmlspecialchars($row['gradelvlID']); ?>">
                                                                        <i class="bi bi-pencil-square"></i>
                                                                    </a>
                                                                    <button class="btn btn-danger delete-btn btn-sm" data-section-id="<?php echo $row['secID']; ?>" data-section-program="<?php echo htmlspecialchars($row['programcode']); ?>">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                    <script>
                                                                        document.querySelectorAll('.dropdown-item').forEach(item => {
                                                                            item.addEventListener('mouseover', function() {
                                                                                this.style.backgroundColor = '#f8f9fa';
                                                                                this.style.color = '#0056b3';
                                                                                this.querySelector('.bi').style.color = '#0056b3';
                                                                            });
                                                                            item.addEventListener('mouseout', function() {
                                                                                this.style.backgroundColor = '';
                                                                                this.style.color = '';
                                                                                this.querySelector('.bi').style.color = '';
                                                                            });
                                                                        });
                                                                    </script>
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

  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
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
                confirmButtonText: 'OK'
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
                text: 'The section has been successfully updated.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear URL parameters after alert
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (updStatus === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message || 'Failed to update the section.',
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