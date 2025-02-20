
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css"> -->

<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if(isset($_SESSION['userID'])) {
  require_once("includes/config.php");

    $query = "SELECT s.*, (SELECT userType FROM user_type WHERE TypeID = s.userTypeID) AS userType 
    FROM users s WHERE s.userID = :userID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<style>
/* General Body and Sidebar Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    padding-top: 60px; /* Ensure space for the fixed header */
}

.sidebar {
    background-color: #ffffff; /* White background for sidebar */
    position: flex;
    top: 50px; /* Start below the fixed header */
    left: 0;
    height: calc(100% - 60px); /* Adjust height to fit below the header */
    width: 270px; /* Fixed width for sidebar */
    padding-top: 20px;
    overflow-y: auto;
    z-index: 1000; /* Ensure it is below the header but above other content */
    border-right: 1px solid #e0e0e0; /* Optional border for separation */
    transition: width 0.3s ease; /* Smooth transition for collapsible effect */
}

.sidebar-header {
    padding-top: 10px;
    /* border-bottom: 2px solid #1a237e; */
    background: #e3f2fd; /* Light Blue Background */
    text-align: center;
    border-radius: 7px;
}

.logo-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.logo-img {
    height: 130px; /* Adjust based on your design */
    width: auto; /* Maintain aspect ratio */
    border-radius: 50%; /* Create the circular shape */
    object-fit: cover; /* Ensure the image covers the container */

}

.school-name {
    font-size: 30px; /* Adjusted font size */
    font-weight: bold; /* Bold for emphasis */
    color: #1a237e; /* Navy Blue */
    margin-top: 1px;
    letter-spacing: 3px; /* Adjust letter spacing as needed */
}


/* Navigation Styles */
.sidebar-nav {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 10px; /* Adjusted spacing between items */
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 15px; /* Adjusted padding */
    border-radius: 5px;
    color: #333;
    text-decoration: none;
    font-size: 16px;
    transition: background-color 0.3s, color 0.3s;
}

.nav-link:hover {
    background-color: #e0e0e0; /* Light Gray Background on Hover */
    color: #1a237e; /* Navy Blue Text on Hover */
}

.nav-link i {
    margin-right: 10px;
}

.nav-content {
    padding-left: 15px;
}

.btn-group .btn {
    border-radius: 20px;
}

.btn-toggle .btn-default {
    background-color: #f4f4f4; /* Light Gray */
}

.btn-toggle .btn-primary {
    background-color: #1a237e; /* Navy Blue */
    color: #ffffff; /* White text */
}

.nav-heading {
    font-size: 18px;
    font-weight: bold;
    color: #1a237e; /* Navy Blue */
    padding: 10px 15px;
}


/* Add light borders to each list item */
.sidebar-nav .nav-item {
    margin-bottom: 0; /* Remove bottom margin to avoid double borders */
}

.sidebar-nav .nav-item:last-child {
    border-bottom: none; /* Remove border from the last item to avoid extra border */
}
.sidebar-content {
    flex: 1; /* Take up remaining space */
    overflow-y: auto;
}

.sidebar-footer {
    padding: 15px;
    background-color: #e3f2fd; /* Light Blue Background */
    border-top: 1px solid #e0e0e0; /* Light Gray Border */
    text-align: center;
}

.welcome-message {
    margin: 0;
    font-size: 12px;
    font-weight: bold;
    color: #1a237e; /* Navy Blue */
}


</style>
<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <img src="assets/img/gmsnlogo.png" alt="Grace Montessori Logo" class="logo-img">
            <span class="school-name">GMSN</span>
        </div>
    </div>

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link collapsed" href="dashboard.php">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="curri.php">
                <i class="bi bi-book-fill"></i>
                <span>Curriculum</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#createStud-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text-fill"></i><span>Student Record</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="createStud-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="sh_studRecord.php" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Create Students</span>
                    </a>
                </li>
                <li>
                    <a href="manage_studentsRec.php" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Manage Students</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#section-builder-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-tools"></i><span>Section Builder</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="section-builder-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <?php 
                require_once("../includes/config.php");
                
                $query = "SELECT * FROM department";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $departments = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                foreach ($departments as $row) {
                    $deptID = $row['deptID'];
                    $deptname = htmlspecialchars($row['deptname']); 
                    echo '<li>
                            <a href="section_builder.php?deptID=' . $deptID . '" style="text-decoration: none">
                                <i class="bi bi-plus-circle"></i><span>' . $deptname . '</span>
                            </a>
                        </li>';
                }
                ?>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#grades-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-star-fill"></i><span>Grades</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="grades-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="grade_record.php">
                        <i class="bi bi-plus-circle"></i><span>Generate Grade Reports</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="users.php">
                <i class="bi bi-person-fill"></i>
                <span>User Accounts</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear-fill"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="settings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#toggleGradeEntryModal" style="text-decoration: none">
                        <i class="bi bi-toggle-on"></i><span>Grade Encoding Permission</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#updateAYModal" style="text-decoration: none">
                        <i class="bi bi-pencil-square"></i> Update AY/Term
                    </a>
                </li>
                <li>
                    <a href="faculty.php" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Faculty</span>
                    </a>
                </li>
                <li>
                    <a href="subjects.php" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Subjects</span>
                    </a>
                </li>
                <li>
                    <a href="grade_level.php" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Grade Level</span>
                    </a>
                </li>
                <li>
                    <a href="programs.php" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Programs</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="sidebar-footer">
        <p class="welcome-message">
            <?php if (isset($user)) : ?>
                Welcome, 
                <?php 
                $title = ($user['gender'] == 'Male') ? 'Mr.' : 'Ms.';
                echo htmlspecialchars($title . ' ' . $user['lname']);
                ?>
            <?php else: ?>
                Welcome, Guest
            <?php endif; ?>
        </p>
    </div>
</aside>

<!-- End Sidebar -->




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



<!-- Modal for Grade Entry Permission -->
<div class="modal fade" id="toggleGradeEntryModal" tabindex="-1" role="dialog" aria-labelledby="toggleGradeEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body p-7">
                <form id="gradePermission" action="save_grade_permission.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Grade Encode Permission</legend>
                        <div class="container mt-5">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="btn-group btn-toggle" id="firstSwitchGroup"> 
                                        <button type="button" class="btn btn-sm btn-primary toggle-btn" data-value="0">OFF</button>
                                        <button type="button" class="btn btn-sm btn-default toggle-btn" data-value="1">ON</button>
                                    </div>
                                    <label for="firstSwitchGroup" class="form-label fw-bold ml-4"><?php echo ($semID == 1) ? 'First Quarter' : 'Third Quarter'; ?></label>
                                    <input type="hidden" name="firstSwitch" id="firstSwitch" value="<?php echo $firstSwitchValue; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="container mt-5">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="btn-group btn-toggle" id="secondSwitchGroup"> 
                                        <button type="button" class="btn btn-sm btn-primary toggle-btn" data-value="0">OFF</button>
                                        <button type="button" class="btn btn-sm btn-default toggle-btn" data-value="1">ON</button>
                                    </div>
                                    <label for="secondSwitchGroup" class="form-label fw-bold ml-4"><?php echo ($semID == 1) ? 'Second Quarter' : 'Fourth Quarter'; ?></label>
                                    <input type="hidden" name="secondSwitch" id="secondSwitch" value="<?php echo $secondSwitchValue; ?>">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateAYBtn">
                            <i class="bi bi-save"></i> Save
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleGroups = ['firstSwitchGroup', 'secondSwitchGroup'];

        toggleGroups.forEach(group => {
            const toggleBtns = document.querySelectorAll(`#${group} .toggle-btn`);
            const hiddenInput = document.getElementById(group.replace('Group', ''));

            // Set the initial active button based on the hidden input value
            toggleBtns.forEach(btn => {
                if (btn.getAttribute('data-value') === hiddenInput.value) {
                    btn.classList.add('active', 'btn-primary');
                } else {
                    btn.classList.remove('active', 'btn-primary');
                }
            });

            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    toggleBtns.forEach(b => b.classList.remove('active', 'btn-primary'));
                    btn.classList.add('active', 'btn-primary');
                    hiddenInput.value = btn.getAttribute('data-value');
                    console.log(`Switch value for ${group} set to: ${hiddenInput.value}`);
                });
            });
        });
    });
</script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> -->




