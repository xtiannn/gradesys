<?php
include 'session.php';
require_once("includes/config.php");
require_once("fetch/fetch_activeAy.php");

$deptID = $_GET['deptID'] ?? '';
$secID = $_GET['secID'] ?? '';


try {
    $fetchAyName = "SELECT ayName FROM sections WHERE secID = :secID";
    $stmtAyName = $conn->prepare($fetchAyName);
    $stmtAyName->bindParam(':secID', $secID, PDO::PARAM_INT);
    $stmtAyName->execute();
    $result = $stmtAyName->fetch(PDO::FETCH_ASSOC);

    $ayName = $result['ayName'];

  } catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage();
  }
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

</head>
<style>
    td{
        font-size: 13px;
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

</style>
<body>

  <?php require_once"support/header.php"?>
  <?php require_once"support/sidebar.php"?>
    <main id="main" class="main">
        <section class="section">
            <div class="custom-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="section_builder.php?deptID=<?php echo $deptID;?>">Sections</a></li>
                      <li class="breadcrumb-item active">Manage Sections</li>
                    </ol>
                </nav>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <h6 class="custom-card-title">
                                    <i class="bi bi-calendar-check me-2"></i>
                                        <?php 
                                        try {
                                                $sqlSecName = "SELECT s.secName, gl.gradelvlcode, s.gradelvlID, s.programID, p.programcode
                                                FROM sections s
                                                JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
                                                LEFT JOIN programs p ON s.programID = p.programID
                                                WHERE s.secID = :secID
                                                AND (s.gradelvlID = :gradelvlID OR s.programID = :programID)";
                                                $stmtSecName = $conn->prepare($sqlSecName);
                                                $stmtSecName->bindParam(':secID', $_GET['secID'], PDO::PARAM_STR);
                                                $stmtSecName->bindParam(':gradelvlID', $_GET['gradelvlID'], PDO::PARAM_INT);
                                                $stmtSecName->bindParam(':programID', $_GET['programID'], PDO::PARAM_INT);
                                                $stmtSecName->execute();

                                                $resultSecName = $stmtSecName->fetch(PDO::FETCH_ASSOC);
                                                
                                                $programname = $resultSecName['programcode'] ?? '';
                                                $sectionname = ucwords(strtolower($resultSecName['secName'])) ?? '';
                                                $gradelvlname = $resultSecName['gradelvlcode'] ?? '';

                                                $secName = "$programname $gradelvlname - $sectionname";
                                        } catch (\Throwable $e) {
                                                echo 'ERROR fetching sec name'.$e->getMessage();
                                        }
                                            echo $secName;
                                        ?>
                                    </h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php 
                                    $deptID = isset($_GET['deptID']) ? intval($_GET['deptID']) : null;
                                    if ($deptID == 3) { ?>
                                    <table class="table table-striped table-bordered datatable">
                                        <?php
                                        $curriculum = [];
                                        if (isset($_GET['programID'], $_GET['gradelvlID'], $_GET['semID'])) {
                                            $programID = $_GET['programID'];
                                            $gradelvlID = $_GET['gradelvlID'];
                                            $semID = $_GET['semID'];
                                            $secID = $_GET['secID'];
                                            $ayID = $_GET['ayID'];

                                            require_once("includes/config.php");

                                            $query = 
                                            "SELECT 
                                                c.*,
                                                s.subjectname AS subjectName,
                                                s.subjectcode AS subjectCode,
                                                CONCAT(f.lname, ', ', f.fname, ' ', f.mname) AS facultyName,
                                                f.lname AS lname,
                                                f.fname AS fname,
                                                f.gender,
                                                fa.facultyAssignID,
                                                fa.facultyID,
                                                fa.schedule
                                            FROM 
                                                curriculum c
                                            LEFT JOIN 
                                                subjects s ON s.subjectID = c.subjectID
                                            LEFT JOIN 
                                                facultyAssign fa ON fa.curriculumID = c.curriculumID
                                                AND fa.secID = :secID
                                            LEFT JOIN 
                                                faculty f ON fa.facultyID = f.facultyID
                                            WHERE 
                                                c.programID = :programID
                                                AND c.gradelvlID = :gradelvlID
                                                AND c.semID = :semID
                                            ORDER BY subjectname ASC";

                                            $stmt = $conn->prepare($query);
                                            $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
                                            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                            $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                                            $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                            $stmt->execute();

                                            $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            $count = 0;
                                        } else {
                                            echo "Not all required parameters are set!";
                                        }
                                        ?>
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Code</th>
                                                <th>Subject</th>
                                                <th>Schedule</th>
                                                <th> Faculty</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($curriculum as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?php echo ++$count; ?>.</td> 
                                                    <td class="text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($row['subjectCode']); ?></td>
                                                    <td class="text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($row['subjectName']); ?></td>

                                                    <td>
                                                        <?php 
                                                            $schedule = htmlspecialchars($row['schedule']); 
                                                            $daysEntries = !empty($schedule) ? explode(', ', $schedule) : []; // Split the schedule into days
                                                            $isLongSchedule = count($daysEntries) > 1; // Show ellipsis if more than 1 entry
                                                            
                                                            $shortSchedule = $isLongSchedule ? $daysEntries[0] . '...' : implode('<br>', $daysEntries); // Show only the first entry with ellipsis if long
                                                        ?>
                                                         <div class="short-schedule" style="cursor: pointer;" onclick="toggleSchedule(this)">
                                                            <?php echo $shortSchedule; ?>
                                                        </div>
                                                        
                                                        <?php if ($isLongSchedule): ?>
                                                            <div class="full-schedule" style="display: none;">
                                                                <?php 
                                                                    // Show the full schedule line by line
                                                                    echo nl2br(implode('<br>', $daysEntries)); 
                                                                ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>

                                                    <script>
                                                        function toggleSchedule(element) {
                                                            const fullSchedule = element.nextElementSibling;
                                                            
                                                            if (fullSchedule.style.display === "none" || fullSchedule.style.display === "") {
                                                                fullSchedule.style.display = "block"; 
                                                                element.style.display = "none"; 
                                                            } else {
                                                                fullSchedule.style.display = "none"; le
                                                                element.style.display = "block"; 
                                                            }
                                                        }
                                                    </script>



                                                    <td style="white-space: nowrap;">
                                                        <?php 
                                                        $gender = $row['gender'];
                                                        $lname = $row['lname'];
                                                        $fname = $row['fname'];
                                                        
                                                        if (!empty($lname) && !empty($fname)) {
                                                            $title = ($gender === 'Male') ? 'Mr. ' : 'Ms. ';
                                                            $firstInitial = strtoupper(substr($fname, 0, 1));
                                                            echo htmlspecialchars($title . $lname . ' ' . $firstInitial . '.');
                                                        } else {
                                                            echo '<span class="badge bg-danger">Not assigned</span>';
                                                        }
                                                        ?>
                                                    </td>

                                                    <td class="text-center">
                                                        <!-- Button for viewing students -->
                                                        <?php
                                                        require_once("includes/functions.php");
                                                        // Define parameters
                                                        $params = [
                                                            'secID' => $secID,
                                                            'programID' => $row['programID'],
                                                            'subjectID' => $row['subjectID'],
                                                            'semID' => $row['semID'],
                                                            'gradelvlID' => $row['gradelvlID'],
                                                            'facultyID' => $row['facultyID'],
                                                            'ayID' => $ayID,
                                                            'deptID' => $deptID
                                                        ];

                                                        // Build URL
                                                        $url = buildUrl('student.php', $params);
                                                        ?>

                                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                                        <!-- <a 
                                                            style="font-size: 12px; height: 31px; background-color: #0d6efd; color: white;" 
                                                            href="<?php //echo $url; ?>"
                                                            data-bs-toggle="tooltip"
                                                            title="Enroll Students"
                                                            class="btn btn-info btn-sm"
                                                            type="button"
                                                            role="button"
                                                            onclick="checkFaculty(event, '<?php //echo urlencode(trim($row['facultyID'])); ?>')" >
                                                            <i style="font-size: 15px;" class="bi bi-people"></i> Students
                                                        </a> -->



                                                        <?php
                                                        // Determine whether to show the "Add Faculty" or "Update Faculty" button
                                                        $hasFaculty = !empty($row['facultyID']) && !empty($row['schedule']);
                                                        ?>

                                                        <!-- Show the "Update Faculty" button if all attributes are present -->
                                                        <?php if ($hasFaculty): ?>
                                                            <button style="font-size: 12px; height: 32px; width: 68px; 
                                                                    <?php echo ($ayName != $activeAY) ? 'display: none;' : ''?>"
                                                                    type="button"
                                                                    class="btn btn-primary btn-sm update-fac-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#UPDATESubjectModal"
                                                                    data-curriculum-id="<?php echo $row['curriculumID']; ?>"
                                                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                                                    data-subject-name="<?php echo $row['subjectName']; ?>"
                                                                    data-faculty-id="<?php echo $row['facultyID']; ?>"
                                                                    data-schedule-time="<?php echo $row['schedule']; ?>"
                                                                    data-ay-id="<?php echo $ayID; ?>"
                                                                    data-sec-id="<?php echo $secID; ?>"
                                                                    data-dept-id="<?php echo $deptID; ?>"
                                                                    data-program-id="<?php echo $programID?>"
                                                                    data-faculty-assign="<?php echo $row['facultyAssignID']; ?>">
                                                                <i style="font-size: 12px;" class="bi bi-pencil-square"></i> Update
                                                            </button>
                                                        <?php else: ?>
                                                            <!-- Show the "Add Faculty" button if any attribute is missing -->
                                                            <button style="font-size: 12px; height: 32px; width: 68px;
                                                                    <?php echo ($ayName != $activeAY) ? 'display: none;' : ''?>"
                                                                    type="button"
                                                                    class="btn btn-primary btn-sm fac-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addSubjectModal"
                                                                    data-curriculum-id="<?php echo $row['curriculumID']; ?>"
                                                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                                                    data-subject-name="<?php echo $row['subjectName']; ?>"
                                                                    data-ay-id="<?php echo $ayID; ?>"
                                                                    data-sec-id="<?php echo $secID; ?>"
                                                                    data-program-id="<?php echo $programID?>"
                                                                    data-dept-id="<?php echo $deptID; ?>">
                                                                <i style="font-size: 12px;" class="bi bi-plus-lg"></i> Assign
                                                            </button>
                                                        <?php endif; ?>

                                                        <!-- Delete button -->
                                                        <button style="<?php echo ($ayName != $activeAY) ? 'display: none;' : ''?>"
                                                            class="btn btn-danger delete-btn btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            title="Unassign Faculty & Schedule"
                                                            data-curr-id="<?php echo $row['curriculumID']; ?>"
                                                            data-sec-id="<?php echo $secID; ?>"
                                                            data-subject-name="<?php echo $row['subjectName']; ?>"
                                                            <?php echo (empty($row['facultyID']) || $row['facultyID'] === '0' || $row['facultyID'] === 'NULL') ? 'disabled': ''?>
                                                            onclick="checkFaculty(event, '<?php echo $row['facultyID']; ?>')">
                                                        <i class="bi bi-person-dash-fill"></i>
                                                        </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php }else{ ?>
                                    <table class="table table-striped table-bordered datatable">
                                    <?php
                                        require_once("includes/config.php");

                                        $secID = isset($_GET['secID']) ? $_GET['secID'] : '';
                                        $subjectID = isset($_GET['subjectID']) ? $_GET['subjectID'] : '';
                                        $gradelvlID = isset($_GET['gradelvlID']) ? $_GET['gradelvlID'] : '';
                                        $facultyID = isset($_GET['facultyID']) ? $_GET['facultyID'] : '';
                                        $ayID = isset($_GET['ayID']) ? $_GET['ayID'] : '';
                                        $facultyName = isset($_GET['facultyName']) ? $_GET['facultyName'] : '';

                                        $curriculum = [];
                                        $count = 0;

                                            try {
                                                $query = "SELECT 
                                                c.curriculumID,
                                                s.subjectID, 
                                                s.subjectname AS subjectName, 
                                                s.subjectcode AS subjectCode,
                                                CONCAT(f.lname, ', ', f.fname, ' ', LEFT(f.mname, 1), '.') AS facultyName,
                                                f.lname AS lname,
                                                f.fname AS fname,
                                                f.gender,
                                                fa.facultyID, 
                                                fa.schedule,
                                                fa.facultyAssignID,
                                                sgl.gradelvlID
                                            FROM 
                                                curriculum c
                                            LEFT JOIN 
                                                subjects s ON s.subjectID = c.subjectID
                                            LEFT JOIN 
                                                subject_grade_levels sgl ON sgl.curriculumID = c.curriculumID
                                            LEFT JOIN 
                                                facultyAssign fa ON fa.curriculumID = c.curriculumID AND fa.secID = :secID 
                                            LEFT JOIN 
                                                faculty f ON fa.facultyID = f.facultyID
                                            WHERE 
                                                sgl.gradelvlID = :gradelvlID
                                            ORDER BY 
                                                c.curriculumID";
                                            
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                                $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                                $stmt->execute();
                                                $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                
                                            } catch (PDOException $e) {
                                                echo "Query failed: " . $e->getMessage();
                                            }
 
                                    ?>

                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Code</th>
                                                <th>Subject</th>
                                                <th class="text-center">Schedule</th>
                                                <th>Faculty</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($curriculum as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?php echo ++$count; ?>.</td> 
                                                    <td><?php echo ($row['subjectCode']); ?></td>
                                                    <td><?php echo ($row['subjectName']); ?></td>
                                                    <td>
                                                        <?php 
                                                            $schedule = htmlspecialchars($row['schedule']); 
                                                            $daysEntries = !empty($schedule) ? explode(', ', $schedule) : []; // Split the schedule into days
                                                            $isLongSchedule = count($daysEntries) > 1; // Show ellipsis if more than 1 entry
                                                            
                                                            $shortSchedule = $isLongSchedule ? $daysEntries[0] . '...' : implode('<br>', $daysEntries); // Show only the first entry with ellipsis if long
                                                        ?>
                                                         <div class="short-schedule" style="cursor: pointer;" onclick="toggleSchedule(this)">
                                                            <?php echo $shortSchedule; ?>
                                                        </div>
                                                        
                                                        <?php if ($isLongSchedule): ?>
                                                            <div class="full-schedule" style="display: none;">
                                                                <?php 
                                                                    // Show the full schedule line by line
                                                                    echo nl2br(implode('<br>', $daysEntries)); 
                                                                ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>

                                                        <script>
                                                            function toggleSchedule(element) {
                                                                const fullSchedule = element.nextElementSibling;
                                                                
                                                                if (fullSchedule.style.display === "none" || fullSchedule.style.display === "") {
                                                                    fullSchedule.style.display = "block"; 
                                                                    element.style.display = "none"; 
                                                                } else {
                                                                    fullSchedule.style.display = "none"; le
                                                                    element.style.display = "block"; 
                                                                }
                                                            }
                                                        </script>                                                    
                                                    <td style="white-space: nowrap;">
                                                        <?php 
                                                        $gender = $row['gender'];
                                                        $lname = $row['lname'];
                                                        $fname = $row['fname'];
                                                        if (!empty($lname && $lname)) {
                                                            $title = ($gender === 'Male') ? 'Mr. ' : 'Ms. ';
                                                            
                                                            $firstInitial = strtoupper(substr($fname, 0, 1));
                                                            echo htmlspecialchars($title . $lname . ' ' . $firstInitial . '.');
                                                        } else {
                                                            echo '<span class="badge bg-danger">Not assigned</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                    <?php 
                                                        require_once("includes/functions.php");
                                                        $paramsJSsec = [
                                                            'secID' => $secID,
                                                            'gradelvlID' => $row['gradelvlID'],
                                                            'subjectID' => $row['subjectID'],
                                                            'facultyID' => $row['facultyID'],
                                                            'ayID' => $ayID,
                                                            'deptID' => 2
                                                        ];
                                                        
                                                        $urlJSsec = buildUrl('student.php', $paramsJSsec);
                                                    ?>
                                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                                        <!-- <a  
                                                            style="font-size: 12px; height: 31px; background-color: #0d6efd; color: white;"
                                                            data-bs-toggle="tooltip"
                                                            title="Enroll Students"  
                                                            href="<?php //echo $urlJSsec?>" 
                                                            class="btn btn-info btn-sm" role="button" 
                                                            onclick="checkFaculty(event, '<?php //echo urlencode(trim($row['facultyID'])); ?>')">
                                                            <i style="font-size: 15px;" class="bi bi-people"></i> Students
                                                        </a> -->
                                                        <?php
                                                        // Determine whether to show the "Add Faculty" or "Update Faculty" button
                                                        $hasFaculty = !empty($row['facultyID']);
                                                        ?>
                                                        
                                                        <?php if ($hasFaculty): ?>
                                                            <!-- Show the "Update Faculty" button if all attributes are present -->
                                                            <button
                                                                style="font-size: 12px; height: 32px; width: 68px; <?php echo ($ayName != $activeAY) ? 'display: none;' : ''?>" 
                                                                type="button" 
                                                                class="btn btn-primary btn-sm update-fac-btn-jhs" 
                                                                data-bs-toggle="modal" data-bs-target="#UPDATESubjectModal" 
                                                                data-curriculum-id="<?php echo $row['curriculumID']; ?>" 
                                                                data-subject-id="<?php echo $row['subjectID']; ?>" 
                                                                data-subject-name="<?php echo $row['subjectName']; ?>"
                                                                data-faculty-id="<?php echo $row['facultyID']; ?>"  
                                                                data-ay-id="<?php echo $ayID; ?>" 
                                                                data-sec-id="<?php echo $secID; ?>"
                                                                data-sec-name="<?php echo $secName; ?>"
                                                                data-dept-id="<?php echo $deptID; ?>"
                                                                data-gradelvl-id="<?php echo $gradelvlID; ?>"
                                                                data-faculty-assign="<?php echo $row['facultyAssignID']; ?>">
                                                                <i style="font-size: 12px;" class="bi bi-pencil-square"></i> Update
                                                            </button>
                                                        <?php else: ?>
                                                            <!-- Show the "Add Faculty" button if any attribute is missing -->
                                                            <button style="font-size: 12px; height: 32px; width: 68px; <?php echo ($ayName != $activeAY) ? 'display: none;' : ''?>" 
                                                                type="button" 
                                                                class="btn btn-primary btn-sm fac-btn" 
                                                                data-bs-toggle="modal" data-bs-target="#addSubjectModal" 
                                                                data-curriculum-id="<?php echo $row['curriculumID']; ?>" 
                                                                data-subject-id="<?php echo $row['subjectID']; ?>" 
                                                                data-subject-name="<?php echo $row['subjectName']; ?>"  
                                                                data-ay-id="<?php echo $ayID; ?>" 
                                                                data-sec-id="<?php echo $secID; ?>"
                                                                data-dept-id="<?php echo $deptID; ?>">
                                                                <i style="font-size: 12px;" class="bi bi-plus-lg"></i> Assign
                                                            </button>
                                                        <?php endif; ?>

                                                        <button class="btn btn-danger delete-btn btn-sm" 
                                                            style="<?php echo ($ayName != $activeAY) ? 'display: none;' : ''?>"
                                                            data-bs-toggle="tooltip"
                                                            title="Unassign Faculty & Schedule"
                                                            data-curr-id="<?php echo $row['curriculumID']; ?>"
                                                            data-sec-id="<?php echo $secID?>" 
                                                            data-subject-name="<?php echo $row['subjectName']; ?>" 
                                                            <?php 
                                                            if (empty($row['facultyID']) || $row['facultyID'] === '0' || $row['facultyID'] === 'NULL') {
                                                                echo 'disabled';
                                                            } 
                                                            ?> 
                                                            onclick="checkFaculty(event, '<?php echo $row['facultyID']; ?>')">
                                                            <i class="bi bi-person-dash-fill"></i>
                                                        </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                                <script>
                                    function checkFaculty(event, facultyID) {
                                        if (facultyID === '' || facultyID === '0') {
                                            event.preventDefault();
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Notice',
                                                text: 'Please assign a faculty member before accessing students.'
                                            });
                                        }
                                    }
                                </script>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        // Initialize Bootstrap tooltips
                                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                            return new bootstrap.Tooltip(tooltipTriggerEl);
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
            </div>
        </section>

       <?php include"modals/subjectModal.php"?>                                                

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
    // Function to get URL parameters
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    // Function to remove the status parameter from the URL
    function removeStatusParameter() {
        var url = new URL(window.location.href);
        url.searchParams.delete('status');
        window.history.replaceState({}, document.title, url.toString());
    }

    // Check for the operation and status parameters
    var operation = getUrlParameter('operation');
    var status = getUrlParameter('status');

    if (operation && status) {
        if (operation === 'insert') {
            if (status === 'success') {
                Swal.fire('Success', 'The faculty assigned successfully.', 'success');
            } else if (status === 'error') {
                Swal.fire('Error', 'There was an error assigning the faculty.', 'error');
            }
        } 
        removeStatusParameter();
    }
</script>


<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script>
function handleDeleteButtonClick(curriculumID, subjectName, secID) {
    Swal.fire({
        title: 'Confirmation',
        text: 'You are about to unassign ' + subjectName + ' as the instructor',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Unassign',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'save_faculty2.php',
                method: 'POST',
                data: {
                    delete: true,  
                    curriculumID: curriculumID,
                    secID: secID
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Unassigned Successfully',
                            text: 'The instructor has been successfully unassigned.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload(); // Optionally refresh the table or section
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: 'Failed to unassign the instructor. Please try again later.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again later.'
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
            var curriculumID = this.getAttribute('data-curr-id');
            var subjectName = this.getAttribute('data-subject-name'); // Get subjectName from button data attribute
            var secID = this.getAttribute('data-sec-id'); // Get secID from button data attribute
            handleDeleteButtonClick(curriculumID, subjectName, secID);
        });
    });
});
</script>
</body>


</html>