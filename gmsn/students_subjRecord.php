<?php
include 'session.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Grade Record</title>
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
      margin-left: -10px;
      margin-right: -15px;
    }
    .custom-container {
      width: 100%; 
    }
    .custom-card-title {
    padding: 1px 0;
    margin: 2px 2px;
    font-size: 15px;
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

    #selGL{
        width: 100%;
    }
    #selSem{
        width: 100%;
    }
    .no-print {
        display: none;
    }
    #student-info p {
        margin-bottom: 0;
        padding: 1;
    }

    .card .hr-wrapper {
        position: relative; 
        width: 100%; 
        margin: -20px -20px 20px -20px; 
    }

    .card hr {
        border: none;
        height: 2px; 
        background-color: black; 
        width: 100%; 
        margin: 2; 
    }
  </style>
</head>

<body>

  <?php require_once "support/header.php" ?>
  <?php require_once "support/sidebar.php" ?>

  <main id="main" class="main mt-0">
    <section class="section">
        <div class="custom-container">
            <?php if (isset($_GET['studID']) || isset($_GET['gradelvlID']) || isset($_GET['semID'])) { 
                $studID = $_GET['studID'];

                $sqlStud = "SELECT CONCAT(s.lname, ', ', s.fname, ' ', s.mname) AS studname,
                            s.lrn FROM students s WHERE s.studID = :studID";
                $stmtStud = $conn->prepare($sqlStud);
                $stmtStud->bindParam(':studID', $studID, PDO::PARAM_INT);
                $stmtStud->execute();
                $resultStud = $stmtStud->fetch(PDO::FETCH_ASSOC);

                $studName = $resultStud['studname'];    
                $lrn = $resultStud['lrn'];    
            ?>
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="grade_record.php">Students' Grades</a></li>
                        <li class="breadcrumb-item active">Grade Records</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <h6 class="custom-card-title fw-normal">Name: <span class="custom-card-title fw-bold ms-1"><?php echo $studName ?></span></h6>
                                <h6 class="custom-card-title fw-normal">LRN: <span class="custom-card-title fw-bold ms-4"><?php echo $lrn ?></span></h6>
                            </div>
                            <div class="d-flex align-items-center mb-0">

                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="fw-bold" for="selAy">Academic Year:</label>
                                            <select name="selAy" id="selAy" class="form-select">
                                                <option value="">All Records</option>
                                                <?php 
                                                    try {
                                                        require_once("includes/config.php");

                                                        $query = "SELECT DISTINCT ayName FROM student_grades WHERE ayName IS NOT NULL";
                                                        $stmt = $conn->prepare($query);
                                                        $stmt->execute();

                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            $selected = (isset($_POST['selAy']) && $_POST['selAy'] == $row['ayName']) ? 'selected' : '';
                                                            echo '<option value="' . $row['ayName'] . '" ' . $selected . '>' . $row['ayName'] . '</option>';
                                                        }
                                                    } catch (PDOException $e) {
                                                        echo '<option disabled>Error fetching academic years</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="selGL" class="fw-bold">Grade Level:</label>
                                            <select name="selGL" id="selGL" class="form-select">
                                                <option value="">All Records</option>
                                                <?php 
                                                    try {
                                                        require_once("includes/config.php");
                                                        $query = "SELECT * FROM grade_level WHERE isActive=1";
                                                        $stmt = $conn->prepare($query);
                                                        $stmt->execute();

                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            $selected = (isset($_POST['selGL']) && $_POST['selGL'] == $row['gradelvlID']) ? 'selected' : '';
                                                            echo '<option value="' . $row['gradelvlID'] . '" ' . $selected . '>' . $row['gradelvl'] . '</option>';
                                                        }
                                                    } catch (PDOException $e) {
                                                        echo '<option disabled>Error fetching grade levels</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="fw-bold" for="selSem">Semester:</label>
                                            <select name="selSem" id="selSem" class="form-select">
                                                <option value="">All Records</option>
                                                <?php 
                                                    try {
                                                        require_once("includes/config.php");
                                                        $query = "SELECT * FROM semester";
                                                        $stmt = $conn->prepare($query);
                                                        $stmt->execute();

                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            $selected = (isset($_POST['selSem']) && $_POST['selSem'] == $row['semID']) ? 'selected' : '';
                                                            echo '<option value="' . $row['semID'] . '" ' . $selected . '>' . $row['semName'] . '</option>';
                                                        }
                                                    } catch (PDOException $e) {
                                                        echo '<option disabled>Error fetching semesters</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <!-- Filter Button -->
                                        <button id="btnFilter" name="btnFilter" class="btn btn-primary" style="margin-top: 46px;">
                                            <i class="bi bi-funnel"></i> Filter
                                        </button>

                                        <!-- Print Button -->
                                        <button id="btnPrint" name="btnPrint" class="btn btn-success no-print" style="margin-top: 46px;" onclick="printTable()">
                                            <i class="bi bi-printer"></i> Print
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-striped table-bordered datatable mt-3" id="printableTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">A.Y.</th>
                                        <th class="text-center">Term</th>
                                        <th>Code</th>
                                        <th>Subject</th>
                                        <th class="text-center">Level</th>
                                        <th class="text-center">Grade</th>
                                        <th class="text-center">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once("includes/config.php");

                                    // Get the student ID from the GET parameter
                                    $studID = $_GET['studID'];

                                    // Base query
                                    $query = "
                                        SELECT ss.*, 
                                            s.subjectcode AS code,
                                            s.subjectname AS subject,
                                            sem.semCode AS term,
                                            gl.gradelvlcode AS gradelvl,
                                            sec.secName AS secName
                                        FROM student_grades ss
                                        JOIN subjects s ON s.subjectID = ss.subjectID
                                        LEFT JOIN semester sem ON sem.semID = ss.semID
                                        JOIN grade_level gl ON gl.gradelvlID = ss.gradelvlID
                                        JOIN sections sec ON sec.secID = ss.secID
                                        WHERE ss.studID = :studID 
                                        AND ss.fgrade IS NOT NULL
                                    ";

                                    // Initialize parameters
                                    $params = [':studID' => $studID];

                                    // Check for filtering conditions
                                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                        if (!empty($_POST['selAy'])) {
                                            $query .= " AND ss.ayName = :selAy";
                                            $params[':selAy'] = $_POST['selAy'];
                                        }
                                        if (!empty($_POST['selGL'])) {
                                            $query .= " AND ss.gradelvlID = :selGL";
                                            $params[':selGL'] = $_POST['selGL'];
                                        }
                                        if (!empty($_POST['selSem'])) {
                                            $query .= " AND ss.semID = :selSem";
                                            $params[':selSem'] = $_POST['selSem'];
                                        }
                                    }

                                    // Order by academic year, grade level, semester, and subject
                                    $query .= " ORDER BY CAST(ss.ayName AS UNSIGNED) DESC, ss.gradelvlID DESC, ss.semID DESC, subject ASC";

                                    // Prepare and execute the query
                                    $stmt = $conn->prepare($query);
                                    foreach ($params as $key => $value) {
                                        $stmt->bindValue($key, $value);
                                    }
                                    $stmt->execute();

                                    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // General average calculation
                                    $generalAverage = 0;
                                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && (!empty($_POST['selAy']) || !empty($_POST['selGL']) || !empty($_POST['selSem']))) {
                                        $totalGrade = 0;
                                        $numSubjects = 0;
                                        foreach ($curriculum as $row) {
                                            if ($row['fgrade'] !== null) {
                                                $totalGrade += $row['fgrade'];
                                                $numSubjects++;
                                            }
                                        }
                                        $generalAverage = $numSubjects > 0 ? $totalGrade / $numSubjects : 0;
                                    }

                                    $count = 0;
                                    foreach ($curriculum as $row):
                                        $section = $row['secName'];
                                        $gradelvlcode = $row['gradelvl'];
                                        $secName = "$gradelvlcode - $section";
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo ++$count; ?>.</td>
                                            <td class="text-center" style="white-space: nowrap"><?php echo htmlspecialchars($row['ayName']); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($row['term'] ?: '-'); ?></td>
                                            <td style="white-space: nowrap"><?php echo htmlspecialchars($row['code']); ?></td>
                                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($row['gradelvl']); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($row['fgrade']); ?></td>
                                            <td class="text-center">
                                                <?php
                                                if ($row['fgrade'] < 75 && $row['fgrade'] !== null) {
                                                    echo "<span class='badge bg-danger'>Failed</span>";
                                                } elseif ($row['fgrade'] === null) {
                                                    echo "<span class='badge bg-secondary'>Incomplete</span>";
                                                } else {
                                                    echo "<span class='badge bg-success'>Passed</span>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (count($curriculum) > 0 && ($generalAverage > 0)) : ?>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-8 text-end">
                                        <strong>General Average:</strong> <?php echo number_format($generalAverage, 2); ?>
                                    </div>
                                    <div id="generalAverage" data-average="<?php echo number_format($generalAverage, 2); ?>" style="display: none;"></div>
                                    <div id="remarks" data-remarks="<?php echo ($generalAverage < 75) ? 'Failed' : 'Passed' ?>" style="display: none;"></div>
                                    <div id="studentName" data-studentName="<?php echo $studName; ?>" style="display: none;"></div>
                                    <div id="sectionName" data-sectionName="<?php echo $secName; ?>" style="display: none;"><?php echo $secName; ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
  </main>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>



  <script>
document.addEventListener('DOMContentLoaded', function() {
  // Get references to the grade level and semester select elements
  const gradeLevelSelect = document.getElementById('selGL');
  const semesterSelect = document.getElementById('selSem');

  // Function to check the grade level and enable/disable semester select
  function toggleSemesterSelect() {
    const selectedGrade = gradeLevelSelect.options[gradeLevelSelect.selectedIndex].text;

    // Check if the selected grade level is between Grade 1 and Grade 10
    if (selectedGrade.startsWith('Grade') && parseInt(selectedGrade.split(' ')[1]) >= 1 && parseInt(selectedGrade.split(' ')[1]) <= 10) {
      semesterSelect.disabled = true;
      semesterSelect.value = ""; // Reset semester selection
    } else {
      semesterSelect.disabled = false;
    }
  }

  // Call the toggle function initially and whenever the grade level changes
  toggleSemesterSelect();
  gradeLevelSelect.addEventListener('change', toggleSemesterSelect);
});

function printTable() {
    // Create a new div element for the print preview
    const printPreview = document.createElement('div');
    printPreview.id = 'print-preview';

    // Get the table and student info
    const table = document.getElementById('printableTable').outerHTML;
    const studentInfo = document.getElementById('student-info') ? document.getElementById('student-info').outerHTML : '';

    // Get the general average from the hidden element or data attribute
    const generalAverageElement = document.getElementById('generalAverage');
    const generalAverage = generalAverageElement ? generalAverageElement.getAttribute('data-average') : 'N/A';

    // Get the student name from the hidden element
    const studentNameElement = document.getElementById('studentName');
    const studentName = studentNameElement ? studentNameElement.getAttribute('data-studentName') : 'Student Name Not Available';
    
    // Get the sec name from the hidden element
    const sectionnameElement = document.getElementById('sectionName');
    const sectionname = sectionnameElement ? sectionnameElement.getAttribute('data-sectionName') : 'Section Name Not Available';
    
    // Get the remarks from the hidden element
    const remarksElement = document.getElementById('remarks');
    const remarksResult = remarksElement ? remarksElement.getAttribute('data-remarks') : 'remarks Not Available';

    // Function to replace badges with text
    function replaceBadgesWithText(html) {
        return html.replace(/<span class="badge[^>]*">([^<]*)<\/span>/g, (match, p1) => {
            return `<span>${p1}</span>`; 
        });
    }

    // Modify the HTML to replace badges with plain text
    const tablePlainText = replaceBadgesWithText(table);
    const studentInfoPlainText = replaceBadgesWithText(studentInfo);

    // Set up the print preview content
    printPreview.innerHTML = `
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #print-preview, #print-preview * {
            visibility: visible;
        }
        #print-preview {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        #print-preview .header {
            display: flex;
            align-items: center;
            justify-content: space-between;  /* Space between logo and text */
            margin-bottom: 20px;
            height: 100px;
            padding-left: 10px;
            padding-right: 10px;
        }
        
        #print-preview .header img {
            max-width: 100px;
            height: auto;
            display: block;
            object-fit: contain;
        }
        
        #print-preview .header .text {
            flex-grow: 1; /* Ensures the text takes available space */
            text-align: center; /* Center the text */
        }
        
        #print-preview .header h3 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            color: black;
            letter-spacing: 0px;
            text-align: center;
        }

        #print-preview .header p {
            margin: 0;
            font-size: 16px;
            color: black;
            text-align: center;
        }

        #print-preview hr {
            border: none;
            height: 2px;
            background-color: black;
            margin: 20px 0;
        }

        .card {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 20px 0 0 0;
            width: auto;
        }

        #print-preview table {
            border-collapse: collapse;
        }

        #print-preview table, #print-preview th, #print-preview td {
            border: none;
        }

        #print-preview th, #print-preview td {
            padding: 8px;
        }

        #print-preview .table {
            width: 100%;
            margin-bottom: 20px;
        }
    }
</style>

<div class="header">
    <img src="assets/img/gmsnlogo.png" alt="School Logo"> 
    <div class="text">
        <h3>GRACE MONTESSORI SCHOOL OF NOVALICHES</h3>
        <p>15 N Aberlardo Street, Doña Rosario Subd., Novaliches Quezon City</p>
    </div>
</div>
<div class="card">
    <p class="mb-0"><strong>Student Name: </strong>${studentName}</p>
    <p><strong>Section: </strong>${sectionname}</p>
    ${studentInfoPlainText}
    ${tablePlainText}
    <hr />
    <p class="mb-0"><strong>General Average: </strong>${generalAverage}</p>
    <p class="mt-0"><strong>Remarks: </strong>${remarksResult}</p>
</div>
    `;

    document.body.appendChild(printPreview);

    window.print();

    document.body.removeChild(printPreview);
}
</script>





</body>

</html>
