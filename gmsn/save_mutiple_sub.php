<?php
include 'session.php';
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
    .bg-warning {
        background-color: #fff3cd;
    }
    .badge.bg-warning {
        font-size: .7rem;
        color: #856404;
        background-color: #fff3cd;
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
    margin-left: 8px;
    }
</style>
<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>

    <main id="main" class="main">
        <section class="section">
            <div class="custom-container">
            <?php
                if (isset($_GET['deptID'])) {
                    $semID = $_GET['semID'] ?? '';
                    $programID = $_GET['programID'] ?? '';
                    $secID = $_GET['secID'] ?? '';
                    $gradelvlID = $_GET['gradelvlID'] ?? '';
                    $ayID = $_GET['ayID'] ?? '';
                    $deptID = $_GET['deptID'] ?? '';
                    $facultyID = $_GET['facultyID'] ?? '';
                }
            ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="section_builder.php?deptID=<?php echo $deptID;?>">Sections</a></li>
                      <li class="breadcrumb-item active">Manage Students</li>
                    </ol>
                </nav>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <h6 class="custom-card-title">
                                <i class="bi bi-folder me-2"></i>
                                    <?php 
                                       try {
                                            $sqlSecName = "SELECT s.secName, gl.gradelvlcode, s.gradelvlID, s.programID, p.programcode
                                            FROM sections s
                                            JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
                                            LEFT JOIN programs p ON s.programID = p.programID
                                            WHERE s.secID = :secID
                                            AND s.gradelvlID = :gradelvlID
                                            OR s.programID = :programID";
                                            $stmtSecName = $conn->prepare($sqlSecName);
                                            $stmtSecName->bindParam(':secID', $_GET['secID'], PDO::PARAM_STR);
                                            $stmtSecName->bindParam(':gradelvlID', $_GET['gradelvlID'], PDO::PARAM_INT);
                                            $stmtSecName->bindParam(':programID', $_GET['programID'], PDO::PARAM_INT);
                                            $stmtSecName->execute();

                                            $resultSecName = $stmtSecName->fetch(PDO::FETCH_ASSOC);
                                            
                                            $programname = $resultSecName['programcode'] ?? '';
                                            $sectionname = $resultSecName['secName'] ?? '';
                                            $gradelvlname = $resultSecName['gradelvlcode'] ?? '';

                                            $secName = "$programname $gradelvlname - $sectionname";
                                       } catch (\Throwable $e) {
                                            echo 'ERROR fetching sec name'.$e->getMessage();
                                       }
                                        echo $secName;
                                    ?>
                                </h6>
                            </div>
                            <div class="d-flex align-items-center mb-0">
                                <?php 
                                    //fetching the active session
                                    try {
                                        $fetchActiveSem = "SELECT semID, ayName FROM academic_year";
                                        $stmtActSem = $conn->prepare($fetchActiveSem);
                                        $stmtActSem->execute();

                                        $semResult = $stmtActSem->fetch(PDO::FETCH_ASSOC);
                                        $activeSemID = $semResult['semID'];
                                        $activeayName = $semResult['ayName'];

                                    } catch (\Throwable $e) {
                                        echo "Error fetching active semester: " . $e->getMessage();
                                    }
                                ?>
                                <?php 
                                    //fetching the ayName of a section
                                    try {
                                        $sectionAyName = "SELECT ayName FROM sections WHERE secID = :secID";    
                                        $stmtAyName = $conn->prepare($sectionAyName);
                                        $stmtAyName->bindParam('secID', $secID, PDO::PARAM_INT);
                                        $stmtAyName->execute();

                                        $resultAyName = $stmtAyName->fetch(PDO::FETCH_ASSOC);
                                        $secAyName = $resultAyName['ayName'];
                                    } catch (\Throwable $e) {
                                        echo "Error fetching academic year: " . $e->getMessage();

                                    }
                                ?>
                                <?php if($deptID == 3):?>
                                    <?php if($secAyName != $activeayName):?>
                                        <?php if($semID != $activeSemID):?>
                                            <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#promoteStudentModal">
                                                <i class="bi bi-arrow-right-circle"></i> Promote All
                                            </a>
                                            <button 
                                                class="btn btn-secondary btn-sm" 
                                                data-bs-toggle="tooltip"
                                                title="You cannot enroll at this time because the AY/SEM of this section has ended and is no longer active."
                                                >
                                                <i class="bi bi-person-add"></i> Enroll Student
                                            </button>
                                        <?php else:?>
                                            <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#promoteStudentModal">
                                                <i class="bi bi-arrow-right-circle"></i> Promote All
                                            </a>
                                            <button 
                                                class="btn btn-secondary btn-sm enroll-stud-btn" 
                                                data-bs-toggle="tooltip"
                                                title="You cannot enroll at this time because the AY/SEM of this section has ended and is no longer active."
                                                >
                                                <i class="bi bi-person-add"></i> Enroll Student
                                            </button> 
                                        <?php endif;?>
                                    <?php else:?>
                                        <?php if($semID != $activeSemID):?>
                                            <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#promoteStudentModal">
                                                <i class="bi bi-arrow-right-circle"></i> Promote All
                                            </a>
                                            <button 
                                                class="btn btn-secondary btn-sm" 
                                                data-bs-toggle="tooltip"
                                                title="You cannot enroll at this time because the AY/SEM of this section has ended and is no longer active."
                                                >
                                                <i class="bi bi-person-add"></i> Enroll Student
                                            </button>
                                        <?php else:?>
                                            <form action="excel/import_students.php" method="POST" enctype="multipart/form-data" class="d-flex align-items-center mt-2">
                                                <div class="input-group me-2">
                                                    <input type="file" name="import_file" id="import_file" class="form-control form-control-sm" style="display: none;" />
                                                    <label for="import_file" class="btn btn-outline-success btn-sm">
                                                        Import Excel
                                                    </label>
                                                    <input type="hidden" name="ayID" value="<?php echo $ayID?>">
                                                    <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID?>">
                                                    <input type="hidden" name="semID" value="<?php echo $semID?>">
                                                    <input type="hidden" name="programID" value="<?php echo $programID?>">
                                                    <input type="hidden" name="secID" value="<?php echo $secID?>">
                                                    <button type="submit" name="enroll_students" style="height: 31px;" class="btn btn-success btn-sm"><i class="bi bi-upload"></i></button>
                                                </div>                            
                                            </form>
                                            <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#promoteStudentModal">
                                                <i class="bi bi-arrow-right-circle"></i> Promote All
                                            </a>
                                            <button 
                                                class="btn btn-primary btn-sm enroll-stud-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#addStudentSec"
                                                data-program-id="<?php echo $programID?>"
                                                data-gradelvl-id="<?php echo $gradelvlID?>"
                                                data-sec-id="<?php echo $secID?>"
                                                data-sec-name="<?php echo $secName?>"
                                                data-dept-id="<?php echo $deptID?>"
                                                data-faculty-id="<?php echo $facultyID?>"
                                                >
                                                <i class="bi bi-person-add"></i> Enroll Student
                                            </button> 
                                        <?php endif;?>
                                    <?php endif?>
                                <?php else:?>
                                    <?php if($secAyName != $activeayName):?>
                                        <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#promoteStudentModal">
                                            <i class="bi bi-arrow-right-circle"></i> Promote All
                                        </a>
                                        <button 
                                            class="btn btn-secondary btn-sm enroll-stud-btn" 
                                            data-bs-toggle="tooltip"
                                            title="You cannot enroll at this time because the A.Y of this section has ended and is no longer active.">
                                            <i class="bi bi-person-add"></i> Enroll Student
                                        </button> 
                                    <?php else:?>
                                        <form action="excel/import_students.php" method="POST" enctype="multipart/form-data" class="d-flex align-items-center mt-2">
                                            <div class="input-group me-2">
                                                <input type="file" name="import_file" id="import_file" class="form-control form-control-sm" style="display: none;" />
                                                <label for="import_file" class="btn btn-outline-success btn-sm">
                                                    Import Excel
                                                </label>
                                                <input type="hidden" name="ayID" value="<?php echo $ayID?>">
                                                <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID?>">
                                                <input type="hidden" name="semID" value="<?php echo $semID?>">
                                                <input type="hidden" name="programID" value="<?php echo $programID?>">
                                                <input type="hidden" name="secID" value="<?php echo $secID?>">
                                                <button type="submit" name="enroll_students" style="height: 31px;" class="btn btn-success btn-sm"><i class="bi bi-upload"></i></button>
                                            </div>                            
                                        </form>
                                        <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#promoteStudentModal">
                                            <i class="bi bi-arrow-right-circle"></i> Promote All
                                        </a>
                                        <button 
                                            class="btn btn-primary btn-sm enroll-stud-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#addStudentSec"
                                            data-program-id="<?php echo $programID?>"
                                            data-gradelvl-id="<?php echo $gradelvlID?>"
                                            data-sec-id="<?php echo $secID?>"
                                            data-sec-name="<?php echo $secName?>"
                                            data-dept-id="<?php echo $deptID?>"
                                            data-faculty-id="<?php echo $facultyID?>"
                                            >
                                            <i class="bi bi-person-add"></i> Enroll Student
                                        </button> 
                                    <?php endif?>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($deptID == 3):?>
                                <?php 
                                $curriculum = [];
                                if (isset($_GET['programID'], $_GET['gradelvlID'], $_GET['semID'])) {
                                    $programID = $_GET['programID'];
                                    $gradelvlID = $_GET['gradelvlID'];
                                    $semID = $_GET['semID'];
                                    $secID = $_GET['secID'];
                                    $facultyID = $_GET['facultyID'];

                                    require_once("includes/config.php");

                                    $query = "SELECT ss.*, s.lrn, s.lname, s.fname, s.mname, s.address, s.contact, s.gender
                                            FROM section_students ss
                                            JOIN students s ON ss.studID = s.studID
                                            WHERE ss.semID = :semID
                                            AND ss.gradelvlID = :gradelvlID
                                            AND secID = :secID
                                            ORDER BY s.lname ASC";

                                    $stmt = $conn->prepare($query);
                                    $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $count = 0;
                                } else {
                                    echo "Not all required parameters are set!";
                                }

                                $processedStudIDs = []; // Array to track processed studIDs
                                ?>

                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 100px">#</th>
                                            <th class="text-center" style="width: 100px">LRN</th>
                                            <th>Name</th>
                                            <?php 
                                            $hasIncomplete = false;
                                            foreach ($curriculum as $row) {
                                                $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                                if ($isIncomplete) {
                                                    $hasIncomplete = true;
                                                    break;
                                                }
                                            }
                                            if ($hasIncomplete) {
                                                echo '<th class="text-center" style="width: 150px">Status</th>';
                                            }
                                            ?>
                                            <th class="text-center" style="width: 150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($curriculum as $row): 
                                            $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);

                                            // Skip duplicate studID
                                            if (in_array($row['studID'], $processedStudIDs)) {
                                                continue; // Skip this row if studID already processed
                                            }

                                            // Add studID to processed list
                                            $processedStudIDs[] = $row['studID'];
                                        ?>
                                            <tr class="<?php echo $isIncomplete ? 'bg-warning' : ''; ?>">
                                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                                <td class="text-center"><?php echo $row['lrn']; ?></td>
                                                <td>
                                                    <?php echo ucwords(strtolower($row['lname'])) . ', ' . ucwords(strtolower($row['fname'])) . ' ' . ucwords(strtolower($row['mname'])); ?>
                                                </td>
                                            <?php if ($hasIncomplete) { ?>
                                                <td class="text-center">
                                                    <?php if ($isIncomplete) { ?>
                                                        <a href="sh_studRecord_update.php?studID=<?php echo $row['studID']?>">
                                                            <span class="badge bg-warning">Incomplete Record</span>
                                                        </a>
                                                    <?php } else { ?>
                                                        <span></span>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                                <td class="text-center">
                                                    <?php
                                                    // Build the URL
                                                    $base_url = 'students_subj.php';
                                                    $params = [
                                                        'studID' => urlencode(trim($row['studID'])),
                                                        'semID' => urlencode(trim($row['semID'])),
                                                        'secID' => urlencode(trim($row['secID'])),
                                                        'gradelvlID' => urlencode(trim($row['gradelvlID'])),
                                                        'programID' => urlencode(trim($row['programID'])),
                                                        'subjectID' => urlencode(0),  // Hardcoded subjectID as 0
                                                        'ayID' => urlencode(trim($row['ayID'])),
                                                        'facultyID' => urlencode(trim($row['facultyID'])),
                                                        'deptID' => urlencode(trim($deptID))
                                                    ];

                                                    // Construct the final URL
                                                    $query_string = http_build_query($params);
                                                    $final_url = $base_url . '?' . $query_string;
                                                    ?>
                                                    <a class="btn btn-primary btn-sm" 
                                                    style="font-size: 13px; height: 31px"
                                                    data-bs-toggle="tooltip"
                                                    title="Enroll Subjects"
                                                    type="button" 
                                                    href="<?php echo $final_url; ?>">
                                                        <i class="bi bi-book me-1"></i> Subjects
                                                    </a>
                                                    <?php if($semID != $activeSemID):?>
                                                    <?php else:?>
                                                        <button class="btn btn-danger delete-btn btn-sm" 
                                                        data-bs-toggle="tooltip"
                                                        title="Unenroll Student"
                                                        data-enroll-id="<?php echo $row['enrollID']; ?>" 
                                                        data-stud-id="<?php echo $row['studID']; ?>">
                                                        <i class="bi bi-person-dash-fill"></i>
                                                    </button>
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            <?php elseif($deptID ==2):?>
                                <?php 
                                $curriculum = [];
                                if(isset($_GET['gradelvlID'])) {
                                    $gradelvlID = $_GET['gradelvlID'];
                                    $secID = $_GET['secID'];
                                    $facultyID = $_GET['facultyID'];

                                    require_once("includes/config.php");

                                    $query = "SELECT ss.*, 
                                        (SELECT lrn FROM students s WHERE ss.studID = s.studID) as lrn,
                                        (SELECT lname FROM students s WHERE ss.studID = s.studID) as lname,
                                        (SELECT fname FROM students s WHERE ss.studID = s.studID) as fname,
                                        (SELECT mname FROM students s WHERE ss.studID = s.studID) as mname,
                                        (SELECT address FROM students s WHERE ss.studID = s.studID) as address,
                                        (SELECT contact FROM students s WHERE ss.studID = s.studID) as contact,
                                        (SELECT gender FROM students s WHERE ss.studID = s.studID) as gender
                                        FROM section_students ss 
                                        WHERE ss.gradelvlID = :gradelvlID 
                                        AND secID = :secID";

                                    $stmt = $conn->prepare($query);
                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                    $count = 0;
                                    } 
                                    else {
                                        echo "Not all required parameters are set!";
                                    }

                                    $processedStudIDs = []; // Array to track processed studIDs

                                ?>
                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 100px">#</th>
                                            <th class="text-center" style="width: 100px">LRN</th>
                                            <th>Name</th>
                                            <?php 
                                                $hasIncomplete = false;
                                                foreach ($curriculum as $row) {
                                                    $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                                    if ($isIncomplete) {
                                                        $hasIncomplete = true;
                                                        break;
                                                    }
                                                }
                                                if ($hasIncomplete) {
                                                    echo '<th class="text-center" style="width: 150px">Status</th>';
                                                }
                                            ?>
                                            <th class="text-center" style="width: 150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($curriculum as $row): 
                                            $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                            // Skip duplicate studID
                                            if (in_array($row['studID'], $processedStudIDs)) {
                                                continue; // Skip this row if studID already processed
                                            }

                                            // Add studID to processed list
                                            $processedStudIDs[] = $row['studID'];
                                        ?>
                                            <tr class="<?php echo $isIncomplete ? 'bg-warning' : ''; ?>">
                                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                                <td class="text-center"><?php echo $row['lrn']; ?></td>
                                                <td>
                                                    <?php echo ucwords(strtolower($row['lname'])) . ', ' . ucwords(strtolower($row['fname'])) . ' ' . ucwords(strtolower($row['mname'])); ?>
                                                </td>
                                            <?php if ($hasIncomplete) { ?>
                                                <td class="text-center">
                                                    <?php if ($isIncomplete) { ?>
                                                        <a href="sh_studRecord_update.php?studID=<?php echo $row['studID']?>">
                                                            <span class="badge bg-warning">Incomplete Record</span>
                                                        </a>
                                                    <?php } else { ?>
                                                        <span></span>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                                <td class="text-center">
                                                <?php
                                                    $studID = urlencode(trim($row['studID']));
                                                    $semID = urlencode(trim($row['semID']));
                                                    $secID = urlencode(trim($row['secID']));
                                                    $gradelvlID = urlencode(trim($row['gradelvlID']));
                                                    $programID = urlencode(trim($row['programID']));
                                                    $studName = urlencode(trim($row['lname']) . ', ' . trim($row['fname']) . ' ' . trim($row['mname']));
                                                    $subjectID = urlencode(0); // Hardcoded as 0
                                                    $ayID = urlencode(trim($row['ayID']));
                                                    $facultyID = urlencode(trim($facultyID));
                                                    $deptID = urlencode(trim($deptID));

                                                    $url = "students_subj.php?studID=$studID&semID=$semID&secID=$secID&gradelvlID=$gradelvlID&programID=$programID&subjectID=$subjectID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID";
                                                ?>

                                                    <a class="btn btn-primary btn-sm" 
                                                    style="font-size: 13px; height: 31px"
                                                        data-bs-toggle="tooltip"
                                                        title="Enroll Subjects"
                                                        type="button" href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="bi bi-book me-1"></i> Subjects
                                                    </a>

                                                    <?php if($secAyName != $activeayName):?>
                                                    <?php else:?>
                                                        <button class="btn btn-danger delete-btn btn-sm" 
                                                        data-bs-toggle="tooltip"
                                                        title="Unenroll Student"
                                                        data-enroll-id="<?php echo $row['enrollID']; ?>" 
                                                        data-stud-id="<?php echo $row['studID']; ?>">
                                                        <i class="bi bi-person-dash-fill"></i>
                                                    </button>
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else:?>
                                <?php 
                                $curriculum = [];
                                if(isset($_GET['gradelvlID'])) {
                                    $gradelvlID = $_GET['gradelvlID'];
                                    $secID = $_GET['secID'];
                                    $facultyID = $_GET['facultyID'];

                                    require_once("includes/config.php");

                                    $query = "SELECT ss.*, 
                                        (SELECT lrn FROM students s WHERE ss.studID = s.studID) as lrn,
                                        (SELECT lname FROM students s WHERE ss.studID = s.studID) as lname,
                                        (SELECT fname FROM students s WHERE ss.studID = s.studID) as fname,
                                        (SELECT mname FROM students s WHERE ss.studID = s.studID) as mname,
                                        (SELECT address FROM students s WHERE ss.studID = s.studID) as address,
                                        (SELECT contact FROM students s WHERE ss.studID = s.studID) as contact,
                                        (SELECT gender FROM students s WHERE ss.studID = s.studID) as gender
                                        FROM section_students ss 
                                        WHERE ss.gradelvlID = :gradelvlID 
                                        AND secID = :secID";

                                    $stmt = $conn->prepare($query);
                                    $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                    $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                    $count = 0;
                                    } 
                                    else {
                                        echo "Not all required parameters are set!";
                                    }

                                    $processedStudIDs = []; // Array to track processed studIDs

                                ?>
                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 100px">#</th>
                                            <th class="text-center" style="width: 100px">LRN</th>
                                            <th>Name</th>
                                            <?php 
                                                $hasIncomplete = false;
                                                foreach ($curriculum as $row) {
                                                    $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                                    if ($isIncomplete) {
                                                        $hasIncomplete = true;
                                                        break;
                                                    }
                                                }
                                                if ($hasIncomplete) {
                                                    echo '<th class="text-center" style="width: 150px">Status</th>';
                                                }
                                            ?>
                                            <th class="text-center" style="width: 150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($curriculum as $row): 
                                            $isIncomplete = empty($row['lname']) || empty($row['fname']) || empty($row['address']) || empty($row['gender']);
                                            // Skip duplicate studID
                                            if (in_array($row['studID'], $processedStudIDs)) {
                                                continue; // Skip this row if studID already processed
                                            }

                                            // Add studID to processed list
                                            $processedStudIDs[] = $row['studID'];
                                        ?>
                                            <tr class="<?php echo $isIncomplete ? 'bg-warning' : ''; ?>">
                                                <td class="text-center"><?php echo ++$count; ?>.</td>
                                                <td class="text-center"><?php echo $row['lrn']; ?></td>
                                                <td>
                                                    <?php echo ucwords(strtolower($row['lname'])) . ', ' . ucwords(strtolower($row['fname'])) . ' ' . ucwords(strtolower($row['mname'])); ?>
                                                </td>
                                            <?php if ($hasIncomplete) { ?>
                                                <td class="text-center">
                                                    <?php if ($isIncomplete) { ?>
                                                        <a href="sh_studRecord_update.php?studID=<?php echo $row['studID']?>">
                                                            <span class="badge bg-warning">Incomplete Record</span>
                                                        </a>
                                                    <?php } else { ?>
                                                        <span></span>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                                <td class="text-center">
                                                <?php
                                                    $studID = urlencode(trim($row['studID']));
                                                    $semID = urlencode(trim($row['semID']));
                                                    $secID = urlencode(trim($row['secID']));
                                                    $gradelvlID = urlencode(trim($row['gradelvlID']));
                                                    $programID = urlencode(trim($row['programID']));
                                                    $studName = urlencode(trim($row['lname']) . ', ' . trim($row['fname']) . ' ' . trim($row['mname']));
                                                    $subjectID = urlencode(0); // Hardcoded as 0
                                                    $ayID = urlencode(trim($row['ayID']));
                                                    $facultyID = urlencode(trim($facultyID));
                                                    $deptID = urlencode(trim($deptID));

                                                    $url = "students_subj.php?studID=$studID&semID=$semID&secID=$secID&gradelvlID=$gradelvlID&programID=$programID&subjectID=$subjectID&ayID=$ayID&facultyID=$facultyID&deptID=$deptID";
                                                ?>

                                                    <a class="btn btn-primary btn-sm" type="button"
                                                        style="font-size: 13px; height: 31px"
                                                        data-bs-toggle="tooltip" title="Enroll Subjects" 
                                                        href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="bi bi-book me-1"></i> Subjects
                                                    </a>

                                                    <?php if($secAyName != $activeayName):?>
                                                    <?php else:?>
                                                        <button class="btn btn-danger delete-btn btn-sm" 
                                                        data-bs-toggle="tooltip"
                                                        title="Unenroll Student"
                                                        data-enroll-id="<?php echo $row['enrollID']; ?>" 
                                                        data-stud-id="<?php echo $row['studID']; ?>">
                                                        <i class="bi bi-person-dash-fill"></i>
                                                    </button>
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif;?>
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
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

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