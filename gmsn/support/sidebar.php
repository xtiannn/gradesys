
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css"> -->

<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$aPage = basename($_SERVER['PHP_SELF']);
if(isset($_SESSION['userID'])) {
  require_once("includes/config.php");

    $query = "SELECT s.*, (SELECT userType FROM user_type WHERE TypeID = s.userTypeID) AS userType 
    FROM users s WHERE s.userID = :userID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch current values and semID
try {
    // Fetch semID
    $sql = "SELECT * FROM academic_year";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $ayID = $result['ayID'];
        $semID = $result['semID'];
        $start = $result['start'];
        $end = $result['end'];
  


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

require_once("modals/AYModal.php");
require_once("modals/gradeSB.php");



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
    width: 270px; 
}
.nav-heading {
  padding: 15px 20px; 
  border-bottom: 1px solid #ddd;
  margin-bottom: 10px; 
}

.nav-heading i {
  margin-right: 10px; 
}
.sidebar .nav-link,
.sidebar .nav-link.collapsed {
    padding: 0.75rem 1rem; 
    display: flex;
    align-items: center;
    text-decoration: none;
}

.sidebar .nav-link i {
    margin-right: 0.5rem;
}

.sidebar button.nav-link {
    background: none;
    border: none;
    width: 100%;
    text-align: left; 
    cursor: pointer; 
}

.sidebar .nav-link:hover,
.sidebar button.nav-link:hover{
    background-color: #E0E0E0  ; 
}

.nav-link.active {
    background-color: #D3D3D3; /* Active item color */
}

/* Style for the button group */
.btn-group .toggle-btn {
    padding: 10px 20px;
    border: 1px solid #ccc;
    cursor: pointer;
    background-color: #f8f9fa; /* Light background */
    transition: background-color 0.3s ease;
}

.btn-group .toggle-btn.active {
    background-color: #007bff; /* Blue background when active */
    color: #fff; /* White text when active */
}

.btn-group .toggle-btn:not(.active) {
    background-color: #f8f9fa; /* Light background when inactive */
    color: #333; /* Dark text when inactive */
}

</style>
<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-heading">
        <?php if (isset($user)) : ?>
            <i class="bi bi-person"></i> 
            <span><?php echo $user['userType'] . ': ' . $user['lname'] . ', ' . $user['fname'][0] . '. ' . $user['mname'][0] . '.'; ?></span>
        <?php endif; ?>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($aPage == 'dashboard.php') ? 'active' : 'collapsed'; ?>" href="dashboard.php">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($aPage == 'curri.php') ? 'active' : 'collapsed'; ?>" href="curri.php">
                <i class="bi bi-book-fill"></i>
                <span>Curriculum</span>
            </a>
        </li>
        <li class="nav-item">
            <button class="nav-link <?php echo ($aPage == 'sh_studRecord.php' || $aPage == 'manage_studentsRec.php') ? 'active' : 'collapsed'; ?>" 
                    data-bs-target="#createStud-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-file-earmark-text-fill"></i>
                <span>Student Record</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </button>
            <ul id="createStud-nav" class="nav-content collapse <?php echo ($aPage == 'sh_studRecord.php' || $aPage == 'manage_studentsRec.php') ? 'show' : 'collapsed'; ?>" 
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="sh_studRecord.php" class="<?php echo ($aPage == 'sh_studRecord.php') ? 'active' : 'collapsed'; ?>" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i>
                        <span>Create Students</span>
                    </a>
                </li>
                <li>
                    <a href="manage_studentsRec.php" class="<?php echo ($aPage == 'manage_studentsRec.php') ? 'active' : 'collapsed'; ?>" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i>
                        <span>Manage Students</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($aPage == 'section_builder.php') ? 'active' : 'collapsed'?>" href="section_builder.php">
            <i class="bi bi-tools"></i>
                <span>Section Builder</span>
            </a>
        </li>
        <li class="nav-item">
            <button class="nav-link <?php echo ($aPage == 'input_grade.php' || $aPage == 'grade_record.php') ? 'active' : 'collapsed'; ?>" data-bs-target="#grades-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-star-fill"></i><span>Grades</span><i class="bi bi-chevron-down ms-auto"></i>
            </button>
            <ul id="grades-nav" class="nav-content collapse <?php echo ($aPage == 'input_grade.php' || $aPage == 'grade_record.php') ? 'show' : 'collapse'?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="input_grade.php" 
                            data-bs-toggle="modal"
                            data-bs-target="#gradeInputSB"
                            data-ay-start="<?php echo $start?>"
                            data-ay-end="<?php echo $end?>"
                            data-ay-term="<?php echo $semID?>"
                        style="text-decoration: none"
                        class="input-gradeSB-btn">
                        <i class="bi bi-star"></i>
                        <span>Input Grade</span>
                    </a>
                    <a href="grade_record.php" class="<?php echo ($aPage == 'grade_record.php') ? 'active' : 'collapsed'; ?>" style="text-decoration: none">
                        <i class="bi bi-award"></i><span>Student Grades Report</span>
                    </a>
                    <!-- <a href="section_reports.php" class="<?php //echo ($aPage == 'section_reports.php') ? 'active' : 'collapsed'; ?>" style="text-decoration: none">
                        <i class="bi bi-award"></i><span>Section Grades Report</span>
                    </a> -->
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($aPage == 'users.php') ? 'active' : 'collapsed'?>" href="users.php">
                <i class="bi bi-person-fill"></i>
                <span>User Accounts</span>
            </a>
        </li>
        <!-- Backup Link -->
        <li class="nav-item">
            <button class="nav-link <?php echo ($aPage == 'faculty.php' || $aPage == 'subjects.php' || $aPage == 'grade_level.php' || $aPage == 'programs.php') ? 'active' : 'collapsed'?>" 
                data-bs-target="#settings-nav" data-bs-toggle="collapse" type="button">
                <i class="bi bi-gear-fill"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
            </button>
            <ul id="settings-nav" class="nav-content collapse <?php echo ($aPage == 'faculty.php' || $aPage == 'subjects.php' || $aPage == 'grade_level.php' || $aPage == 'programs.php') ? 'show' : 'collapsed'?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#toggleGradeEntryModal" style="text-decoration: none">
                        <i class="bi bi-toggle-on"></i><span>Grade Encoding Permission</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-bs-toggle="modal" class="updateAY-btn" data-bs-target="#updateAYModal" style="text-decoration: none"
                        data-ay-id="<?php echo $ayID?>" data-ay-start="<?php echo $start?>" data-ay-end="<?php echo $end?>" data-ay-term="<?php echo $semID?>">
                        <i class="bi bi-pencil-square"></i> Update AY/Term
                    </a>
                </li>
                <!-- <li>
                    <a href="faculty.php" class="<?php //echo ($aPage == 'faculty.php') ? 'active' : 'collapsed'; ?>" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Faculty</span>
                    </a>
                </li> -->
                <li>
                    <a href="subjects.php" class="<?php echo ($aPage == 'subjects.php') ? 'active' : 'collapsed'; ?>" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Subjects</span>
                    </a>
                </li>
                <li>
                    <a href="grade_level.php" class="<?php echo ($aPage == 'grade_level.php') ? 'active' : 'collapsed'; ?>" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Grade Level</span>
                    </a>
                </li>
                <li>
                    <a href="programs.php" class="<?php echo ($aPage == 'programs.php') ? 'active' : 'collapsed'; ?>" style="text-decoration: none">
                        <i class="bi bi-plus-circle"></i><span>Programs</span>
                    </a>
                </li>
                <li>
                    <a href="#" id="showBackupsButton" data-bs-toggle="modal" class="backup-btn" data-bs-target="#viewBackupsModal" style="text-decoration: none">
                    <i class="bi bi-arrow-clockwise"></i>
                        <span>Data Backup & Recovery</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>

<!-- End Sidebar -->


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
<!-- Modal for Backup -->
<div class="modal fade" id="backupModal" tabindex="-1" role="dialog" aria-labelledby="backupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body p-7">
                <form id="backupForm" action="includes/backup.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Create Backup</legend>
                        <div class="mb-3">
                            <label for="backupName" class="form-label fw-bold">Backup Name: </label>
                            <input type="text" class="form-control" id="backupName" name="backupName" placeholder="Enter a filename" required>
                            <div class="invalid-feedback">Please provide a backup name.</div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-info me-md-2" id="showBackupsButton" data-bs-toggle="modal" data-bs-target="#viewBackupsModal">
                            <i class="bi bi-folder"></i> Show Backups
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Showing Backups -->
<div class="modal fade" id="viewBackupsModal" tabindex="-1" role="dialog" aria-labelledby="viewBackupsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-3">
                <legend>Backup File</legend>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Backup Name</th>
                                <th>Date Created</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="backupFileList">
                            
                        </tbody>
                    </table>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <form action="includes/backup.php">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update
                        </button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('showBackupsButton').addEventListener('click', function() {
        const backupFileList = document.getElementById('backupFileList');
        backupFileList.innerHTML = ''; 

        fetch('includes/get_backups.php')
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(file => {
                        const row = document.createElement('tr');

                        const nameCell = document.createElement('td');
                        nameCell.textContent = file.name;

                        const dateCell = document.createElement('td');
                        dateCell.textContent = file.date;

                        const actionCell = document.createElement('td');
                        actionCell.className = 'text-center';

                        // Create the download link
                        const downloadLink = document.createElement('a');
                        downloadLink.href = `../backup/${file.name}`; 
                        downloadLink.className = 'btn btn-primary btn-sm me-1';
                        downloadLink.target = '_blank'; 

                        // Create the download icon
                        const downloadIcon = document.createElement('i');
                        downloadIcon.className = 'bi bi-download'; 
                        downloadLink.appendChild(downloadIcon);
                        downloadLink.appendChild(document.createTextNode('')); 

                        actionCell.appendChild(downloadLink);


                        // Append cells to the row
                        row.appendChild(nameCell);
                        row.appendChild(dateCell);
                        row.appendChild(actionCell);
                        
                        backupFileList.appendChild(row);
                    });
                } else {
                    backupFileList.innerHTML = '<tr><td colspan="3" class="text-center">No backup files found.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching backup files:', error);
                backupFileList.innerHTML = '<tr><td colspan="3" class="text-center">Error loading backup files.</td></tr>';
            });
    });

    
// Function to get query parameters from the URL
function getQueryParams() {
    const params = {};
    window.location.search.substring(1).split('&').forEach(function(pair) {
        const [key, value] = pair.split('=');
        params[decodeURIComponent(key)] = decodeURIComponent(value || '');
    });
    return params;
}

const queryParams = getQueryParams();
if (queryParams.backupstatus) {
    const alertConfig = queryParams.backupstatus === 'success' ? {
        icon: 'success',
        title: 'Success!',
        text: 'Backup updated successfully!',
        confirmButtonText: 'OK',
        showCancelButton: false
    } : {
        icon: 'error',
        title: 'Error!',
        text: 'Failed to update the backup. Please try again.',
        confirmButtonText: 'OK'
    };

    Swal.fire(alertConfig).then((result) => {
        if (result.isConfirmed) {
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.close(); 
            $('#viewBackupsModal').modal('show'); 
        }
    });
}

</script>






<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleGroups = ['firstSwitchGroup', 'secondSwitchGroup'];

        toggleGroups.forEach(group => {
            const toggleBtns = document.querySelectorAll(`#${group} .toggle-btn`);
            const hiddenInput = document.getElementById(group.replace('Group', ''));

            // Initialize the toggle buttons without colors
            toggleBtns.forEach(btn => {
                if (btn.getAttribute('data-value') === hiddenInput.value) {
                    // Apply active class based on hidden input value
                    btn.classList.add('active'); // Use the active class to indicate the selected button
                } else {
                    // If not selected, remove the active class
                    btn.classList.remove('active');
                }
            });

            // Handle button click event
            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const isOn = btn.getAttribute('data-value') === '1';

                    // Handle the click based on the value (ON or OFF)
                    if (isOn) {
                        // Handle the case where the "ON" button is clicked again
                        if (!btn.classList.contains('active')) {
                            // Set the clicked button to ON
                            toggleBtns.forEach(b => {
                                if (b.getAttribute('data-value') === '1') {
                                    b.classList.add('active'); // ON button becomes active
                                } else {
                                    b.classList.remove('active'); // OFF button is deactivated
                                }
                            });
                            hiddenInput.value = '1'; // Update hidden input for ON

                            // Deselect the "ON" of the other switch (if necessary)
                            if (group === 'firstSwitchGroup') {
                                const secondToggleBtns = document.querySelectorAll(`#secondSwitchGroup .toggle-btn`);
                                secondToggleBtns.forEach(b => {
                                    if (b.getAttribute('data-value') === '1') {
                                        b.classList.remove('active'); // Turn off second switch if it's ON
                                    }
                                });
                                document.getElementById('secondSwitch').value = '0'; // Update second switch hidden input
                            }

                            if (group === 'secondSwitchGroup') {
                                const firstToggleBtns = document.querySelectorAll(`#firstSwitchGroup .toggle-btn`);
                                firstToggleBtns.forEach(b => {
                                    if (b.getAttribute('data-value') === '1') {
                                        b.classList.remove('active'); // Turn off first switch if it's ON
                                    }
                                });
                                document.getElementById('firstSwitch').value = '0'; // Update first switch hidden input
                            }
                        }
                    } else {
                        // Set the clicked button to OFF
                        toggleBtns.forEach(b => {
                            if (b.getAttribute('data-value') === '0') {
                                b.classList.add('active'); // OFF button becomes active
                            } else {
                                b.classList.remove('active'); // ON button is deactivated
                            }
                        });
                        hiddenInput.value = '0'; // Update hidden input for OFF
                    }

                    console.log(`Switch value for ${group} set to: ${hiddenInput.value}`);
                });
            });
        });
    });
</script>




