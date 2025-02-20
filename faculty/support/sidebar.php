<?php
$aPage = basename($_SERVER['PHP_SELF']);
if(isset($_SESSION['userID'])) {
  require_once("includes/config.php");

    $query = "SELECT s.*, (SELECT userType FROM user_type WHERE TypeID = s.userTypeID) AS userType 
    FROM users s WHERE s.userID = :userID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $uTypeID = $user['userTypeID'];
    $_SESSION['uTypeID'] = $uTypeID;



    // Fetch facultyID for the specific user
    $sqlFacultyID = "SELECT facultyID FROM faculty WHERE facultyNum = :facultyNum";

    $stmtFacultyID = $conn->prepare($sqlFacultyID);
    $stmtFacultyID->bindParam(':facultyNum', $user['userID'], PDO::PARAM_STR);
    $stmtFacultyID->execute();
    
    $facultyIDResult = $stmtFacultyID->fetch(PDO::FETCH_ASSOC);
    
    $facultyID = $facultyIDResult['facultyID'];

    $_SESSION['facultyID'] = $facultyID;
}
?>

<style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    padding-top: 60px; 
}

.sidebar {
    width: 270px; /* Fixed width for sidebar */
}
.nav-heading {
  padding: 15px 20px; /* Adjust padding as needed */
  border-bottom: 1px solid #ddd; /* Light gray border for the line */
  margin-bottom: 10px; /* Space below the line */
}

.nav-heading i {
  margin-right: 10px; /* Space between the icon and the text */
}

.nav-link.active {
    background-color: #D3D3D3; /* Active item color */
}
</style>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">
      <?php if(isset($user)) : ?>
        <?php 
          $prefix = ($user['userType'] == 'Admin') ? 'Admin: ' : 'Faculty: ';
          $gender = ($user['gender'] === 'Male') ? 'Mr. ' : 'Ms. ';  
          $fullName = $user['lname'].', '. substr($user['fname'], 0, 1). '.';
          echo '<i class="bi bi-person"></i>' . $prefix . $gender . $fullName;
        ?>
      <?php endif; ?>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo ($aPage == 'index.php') ? 'active' : 'collapsed'?>" href="index.php">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo ($aPage == 'assignedSub.php') ? 'active' : 'collapsed'?>" href="assignedSub.php">
      <i class="bi bi-clipboard-fill"></i>
      <span>Assigned Subjects</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo ($aPage == 'advisory.php') ? 'active' : 'collapsed'?>" href="advisory.php">
        <i class="bi bi-people-fill"></i>
        <span>Advisory Class</span>
      </a>
    </li>



    <!-- <li class="nav-item">
      <button class="nav-link collapsed" data-bs-toggle="modal" data-bs-target="#archivedRecordsModal">
          <i class="bi bi-archive-fill"></i>
          <span>Archived Records</span>
      </butt>
    </li> -->
  </ul>
</aside><!-- End Sidebar-->




<?php
require_once("modals/AYModal.php");
require_once 'includes/config.php';

// Fetch current values and semID
try {
    // Fetch semID
    $sql = "SELECT semID FROM academic_year";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $semID = $result['semID'];

    // Fetch current switch values
    $sql = "SELECT _first, _second FROM gradepermission"; 
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $firstSwitchValue = $result['_first'];
    $secondSwitchValue = $result['_second'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!-- Modal for archived records-->
<div class="modal fade" id="archivedRecordsModal" tabindex="-1" aria-labelledby="archivedRecordsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="archived.php" method="get" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">View Archived Records</legend>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selYear" class="form-label fw-bold">Academic Year:</label>
                                <select class="form-select form-control selectpicker" data-live-search="true" name="ayName" id="selYear" required>
                                    <option selected value="" disabled>Select Year: </option>
                                    <?php 
                                        try {
                                            $sql = "SELECT DISTINCT s.ayName
                                                    FROM sections s
                                                    WHERE s.facultyID = :facultyID AND s.isActive = 0
                                                    ORDER BY s.ayName DESC";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
                                            $stmt->execute();
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo '<option value="' . htmlspecialchars($row['ayName']) . '">' . htmlspecialchars($row['ayName']) . '</option>';
                                            }
                                        } catch (\Throwable $e) {
                                            echo '<option value="" disabled>Error fetching years</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-12 mb-3">
                            <label for="selSem" class="form-label fw-bold">Semester: <span class="fw-normal">(Don't Select if not SHS)</span></label>
                            <select class="form-select form-control selectpicker" name="semID" id="selSem">
                                <option selected value="" disabled>Select Semester</option> 
                                <?php 
                                    try {
                                        $sql = "SELECT * FROM semester";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                            echo '<option value="' . htmlspecialchars($row['semID']) . '">' . htmlspecialchars($row['semName']) . '</option>';
                                        }
                                    } catch (\Throwable $e) {
                                        echo '<option value="" disabled>Error fetching semesters</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        </div>
                        <div class="row">
                          <div class="col-md-12 mb-3">
                              <label for="actID" class="form-label fw-bold">Select Record:</label>
                              <select class="form-select form-control selectpicker" name="actID" id="actID" required>
                                  <option selected value="" disabled>Select Record: </option>
                                  <option value="1">Assigned Subjects</option>
                                  <option value="2">Assigned Classes</option>
                              </select>
                          </div>
                        </div>
                        <!-- Hidden input for faculty ID -->
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="">
                            <i class="bi bi-folder me-1"></i> View
                        </button>
                        <button type="button" class="btn btn-secondary" name="cancelBtn" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php
require_once "includes/config.php";

if (isset($_GET['updProfstatus']) && $_GET['updProfstatus'] === 'success') {
    echo '<script src="assets/sweetalert2.all.min.js"></script>';
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Successfully Updated",
            text: "Your account details have been successfully updated.",
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    </script>';
}
?>
