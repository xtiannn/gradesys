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
                <?php 
                    $deptID = isset($_GET['deptID']) ? $_GET['deptID'] : 0;
                ?>
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
                                        $secName = isset($_GET['secName']) ? $_GET['secName'] : '';
                                        echo $secName 
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

                                            $query = "
                                            SELECT 
                                                c.*,
                                                s.subjectname AS subjectName,
                                                s.subjectcode AS subjectCode,
                                                CONCAT(f.lname, ', ', f.fname, ' ', f.mname) AS facultyName,
                                                fa.facultyID,
                                                fa.day,
                                                fa.startTime,
                                                fa.endTime,
                                                fa.facultyAssignID
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
                                                AND c.semID = :semID";

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
                                                <th>Day</th>
                                                <th class="text-center">Time</th>
                                                <th> Faculty</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($curriculum as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?php echo ++$count; ?>.</td> 
                                                    <td><?php echo htmlspecialchars($row['subjectCode']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['subjectName']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['day']); ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            if (!empty($row['startTime']) && !empty($row['endTime'])) {
                                                                $startTime = date('h:i A', strtotime($row['startTime']));
                                                                $endTime = date('h:i A', strtotime($row['endTime']));
                                                                echo $startTime . ' - ' . $endTime; 
                                                            } elseif (empty($row['startTime']) && empty($row['endTime'])) {
                                                                echo "";
                                                            } else {
                                                                echo "00:00 AM - 00:00 AM";
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        if (!empty($row['facultyName'])) {
                                                            echo htmlspecialchars($row['facultyName']);
                                                        } else {
                                                            echo '<span class="badge bg-danger">Not assigned</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <!-- Button for viewing students -->
                                                        <a style="font-size: 12px; height: 31px;" 
                                                        href="student.php?
                                                            secID=<?php echo urlencode(trim($secID)); ?>
                                                            &secName=<?php echo urlencode(trim($secName)); ?>
                                                            &subjectName=<?php echo urlencode(trim($row['subjectName'])); ?>
                                                            &programID=<?php echo urlencode(trim($row['programID'])); ?>
                                                            &subjectID=<?php echo urlencode(trim($row['subjectID'])); ?>
                                                            &semID=<?php echo urlencode(trim($row['semID'])); ?>
                                                            &gradelvlID=<?php echo urlencode(trim($row['gradelvlID'])); ?>
                                                            &facultyID=<?php echo urlencode(trim($row['facultyID'])); ?>
                                                            &ayID=<?php echo urlencode(trim($ayID)); ?>
                                                            &facultyName=<?php echo urlencode(trim($row['facultyName'])); ?>
                                                            &deptID=<?php echo urlencode(trim($deptID)); ?>"
                                                        class="btn btn-info btn-sm"
                                                        role="button"
                                                        onclick="checkFaculty(event, '<?php echo urlencode(trim($row['facultyID'])); ?>')">
                                                        <i style="font-size: 15px;" class="bi bi-people"></i> Students
                                                        </a>

                                                        <?php
                                                        // Determine whether to show the "Add Faculty" or "Update Faculty" button
                                                        $hasFaculty = !empty($row['facultyID']) && !empty($row['day']) && !empty($row['startTime']) && !empty($row['endTime']);
                                                        ?>

                                                        <!-- Show the "Update Faculty" button if all attributes are present -->
                                                        <?php if ($hasFaculty): ?>
                                                            <button style="font-size: 12px; height: 32px; width: 68px;"
                                                                    type="button"
                                                                    class="btn btn-primary btn-sm update-fac-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#UPDATESubjectModal"
                                                                    data-curriculum-id="<?php echo $row['curriculumID']; ?>"
                                                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                                                    data-subject-name="<?php echo $row['subjectName']; ?>"
                                                                    data-faculty-id="<?php echo $row['facultyID']; ?>"
                                                                    data-start-time="<?php echo $row['startTime']; ?>"
                                                                    data-end-time="<?php echo $row['endTime']; ?>"
                                                                    data-subject-day="<?php echo $row['day']; ?>"
                                                                    data-ay-id="<?php echo $ayID; ?>"
                                                                    data-sec-id="<?php echo $secID; ?>"
                                                                    data-dept-id="<?php echo $deptID; ?>">
                                                                <i style="font-size: 12px;" class="bi bi-pencil-square"></i> Update
                                                            </button>
                                                        <?php else: ?>
                                                            <!-- Show the "Add Faculty" button if any attribute is missing -->
                                                            <button style="font-size: 12px; height: 32px; width: 68px;"
                                                                    type="button"
                                                                    class="btn btn-primary btn-sm fac-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addSubjectModal"
                                                                    data-curriculum-id="<?php echo $row['curriculumID']; ?>"
                                                                    data-subject-id="<?php echo $row['subjectID']; ?>"
                                                                    data-subject-name="<?php echo $row['subjectName']; ?>"
                                                                    data-ay-id="<?php echo $ayID; ?>"
                                                                    data-sec-id="<?php echo $secID; ?>"
                                                                    data-dept-id="<?php echo $deptID; ?>">
                                                                <i style="font-size: 12px;" class="bi bi-plus-lg"></i> Assign
                                                            </button>
                                                        <?php endif; ?>

                                                        <!-- Delete button -->
                                                        <button class="btn btn-danger delete-btn btn-sm"
                                                                data-curr-id="<?php echo $row['curriculumID']; ?>"
                                                                data-sec-id="<?php echo $secID; ?>"
                                                                data-subject-name="<?php echo $row['subjectName']; ?>"
                                                                <?php if (empty($row['facultyID']) || $row['facultyID'] === '0' || $row['facultyID'] === 'NULL'): ?>
                                                                    disabled
                                                                <?php endif; ?>
                                                                onclick="checkFaculty(event, '<?php echo $row['facultyID']; ?>')">
                                                            <i class="bi bi-person-dash-fill"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php }elseif ($deptID == 2) { ?>
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
                                                fa.facultyID, 
                                                fa.day, 
                                                fa.startTime, 
                                                fa.endTime, 
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
                                                <th>Day</th>
                                                <th class="text-center">Time</th>
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
                                                    <td><?php echo ($row['day']); ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            if (!empty($row['startTime']) && !empty($row['endTime'])) {
                                                                $startTime = date('h:i A', strtotime($row['startTime']));
                                                                $endTime = date('h:i A', strtotime($row['endTime']));
                                                                
                                                                echo $startTime . ' - ' . $endTime; 
                                                            } elseif (empty($row['startTime']) && empty($row['endTime'])) {
                                                                echo "";
                                                            } else {
                                                                echo "00:00 AM - 00:00 AM";
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        if (!empty($row['facultyName'])) {
                                                            echo htmlspecialchars($row['facultyName']);
                                                        } else {
                                                            echo '<span class="badge bg-danger">Not assigned</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a style="font-size: 12px; height: 31px" 
                                                        href="student.php?secID=<?php echo urlencode(trim($secID)); ?>
                                                        &secName=<?php echo urlencode(trim($secName)); ?>
                                                        &subjectName=<?php echo urlencode(trim($row['subjectName'])); ?>
                                                        &subjectID=<?php echo urlencode(trim($row['subjectID'])); ?>
                                                        &gradelvlID=<?php echo urlencode(trim($row['gradelvlID'])); ?>
                                                        &facultyID=<?php echo urlencode(trim($row['facultyID'])); ?>
                                                        &ayID=<?php echo urlencode(trim($ayID)); ?>
                                                        &facultyName=<?php echo urlencode(trim($row['facultyName'])); ?>
                                                        &deptID=<?php echo urlencode(trim($deptID)); ?>" 
                                                        class="btn btn-info btn-sm" role="button" 
                                                        onclick="checkFaculty(event, '<?php echo urlencode(trim($row['facultyID'])); ?>')">
                                                        <i style="font-size: 15px;" class="bi bi-people"></i> Students
                                                        </a>
                                                        <?php
                                                        // Determine whether to show the "Add Faculty" or "Update Faculty" button
                                                        $hasFaculty = !empty($row['facultyID']) && !empty($row['day']) && !empty($row['startTime']) && !empty($row['endTime']);
                                                        ?>
                                                        
                                                        <?php if ($hasFaculty): ?>
                                                            <!-- Show the "Update Faculty" button if all attributes are present -->
                                                            <button style="font-size: 12px; height: 32px; width: 68px;" type="button" class="btn btn-primary btn-sm update-fac-btn" 
                                                                data-bs-toggle="modal" data-bs-target="#UPDATESubjectModal" 
                                                                data-curriculum-id="<?php echo $row['curriculumID']; ?>" 
                                                                data-subject-id="<?php echo $row['subjectID']; ?>" 
                                                                data-subject-name="<?php echo $row['subjectName']; ?>"
                                                                data-faculty-id="<?php echo $row['facultyID']; ?>"  
                                                                data-start-time="<?php echo $row['startTime']; ?>" 
                                                                data-end-time="<?php echo $row['endTime']; ?>" 
                                                                data-subject-day="<?php echo $row['day']; ?>"
                                                                data-ay-id="<?php echo $ayID; ?>" 
                                                                data-sec-id="<?php echo $secID; ?>"
                                                                data-dept-id="<?php echo $deptID; ?>">
                                                                <i style="font-size: 12px;" class="bi bi-pencil-square"></i> Update
                                                            </button>
                                                        <?php else: ?>
                                                            <!-- Show the "Add Faculty" button if any attribute is missing -->
                                                            <button style="font-size: 12px; height: 32px; width: 68px;" type="button" class="btn btn-primary btn-sm fac-btn" 
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
                                                fa.facultyID, 
                                                fa.day, 
                                                fa.startTime, 
                                                fa.endTime, 
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
                                                <th>Day</th>
                                                <th class="text-center">Time</th>
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
                                                    <td><?php echo ($row['day']); ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            if (!empty($row['startTime']) && !empty($row['endTime'])) {
                                                                $startTime = date('h:i A', strtotime($row['startTime']));
                                                                $endTime = date('h:i A', strtotime($row['endTime']));
                                                                
                                                                echo $startTime . ' - ' . $endTime; 
                                                            } elseif (empty($row['startTime']) && empty($row['endTime'])) {
                                                                echo "";
                                                            } else {
                                                                echo "00:00 AM - 00:00 AM";
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        if (!empty($row['facultyName'])) {
                                                            echo htmlspecialchars($row['facultyName']);
                                                        } else {
                                                            echo '<span class="badge bg-danger">Not assigned</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a style="font-size: 12px; height: 31px" 
                                                        href="student.php?secID=<?php echo urlencode(trim($secID)); ?>
                                                        &secName=<?php echo urlencode(trim($secName)); ?>
                                                        &subjectName=<?php echo urlencode(trim($row['subjectName'])); ?>
                                                        &subjectID=<?php echo urlencode(trim($row['subjectID'])); ?>
                                                        &gradelvlID=<?php echo urlencode(trim($row['gradelvlID'])); ?>
                                                        &facultyID=<?php echo urlencode(trim($row['facultyID'])); ?>
                                                        &ayID=<?php echo urlencode(trim($ayID)); ?>
                                                        &facultyName=<?php echo urlencode(trim($row['facultyName'])); ?>
                                                        &deptID=<?php echo urlencode(trim($deptID)); ?>" 
                                                        class="btn btn-info btn-sm" role="button" 
                                                        onclick="checkFaculty(event, '<?php echo urlencode(trim($row['facultyID'])); ?>')">
                                                        <i style="font-size: 15px;" class="bi bi-people"></i> Students
                                                        </a>
                                                        <?php
                                                        // Determine whether to show the "Add Faculty" or "Update Faculty" button
                                                        $hasFaculty = !empty($row['facultyID']) && !empty($row['day']) && !empty($row['startTime']) && !empty($row['endTime']);
                                                        ?>
                                                        
                                                        <?php if ($hasFaculty): ?>
                                                            <!-- Show the "Update Faculty" button if all attributes are present -->
                                                            <button style="font-size: 12px; height: 32px; width: 68px;" type="button" class="btn btn-primary btn-sm update-fac-btn" 
                                                                data-bs-toggle="modal" data-bs-target="#UPDATESubjectModal" 
                                                                data-curriculum-id="<?php echo $row['curriculumID']; ?>" 
                                                                data-subject-id="<?php echo $row['subjectID']; ?>" 
                                                                data-subject-name="<?php echo $row['subjectName']; ?>"
                                                                data-faculty-id="<?php echo $row['facultyID']; ?>"  
                                                                data-start-time="<?php echo $row['startTime']; ?>" 
                                                                data-end-time="<?php echo $row['endTime']; ?>" 
                                                                data-subject-day="<?php echo $row['day']; ?>"
                                                                data-ay-id="<?php echo $ayID; ?>" 
                                                                data-sec-id="<?php echo $secID; ?>"
                                                                data-dept-id="<?php echo $deptID; ?>">
                                                                <i style="font-size: 12px;" class="bi bi-pencil-square"></i> Update
                                                            </button>
                                                        <?php else: ?>
                                                            <!-- Show the "Add Faculty" button if any attribute is missing -->
                                                            <button style="font-size: 12px; height: 32px; width: 68px;" type="button" class="btn btn-primary btn-sm fac-btn" 
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
        text: 'Are you sure you want to unassign ' + subjectName + ' as the instructor?',
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