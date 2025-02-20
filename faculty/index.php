<?php
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../gmsn/assets/img/gmsnlogo.png" rel="icon">
    <link href="../gmsn/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
     <link rel="stylesheet" href="../gmsn/assets/google-fonts.css">

    <!-- Vendor CSS Files -->
    <link href="../gmsn/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../gmsn/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../gmsn/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../gmsn/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../gmsn/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../gmsn/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../gmsn/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../gmsn/assets/css/style.css" rel="stylesheet">

    <link rel="stylesheet" href="../gmsn/assets/calendar-main.min.css">

    <style>


        .chart-container {
            width: 100%;
            height: 250px; 
            margin-bottom: 20px; 
        }



        #calendar {
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px; 
        }

        .main {
            padding: 20px; 
        }

        @media (max-width: 768px) {
            .card {
                height: 80px; 
            }

            .chart-container {
                height: 200px; 
            }

            .card-body {
                padding: 10px;
            }
        }
        .fc .fc-daygrid-day-number {
            font-size: 12px; 
            font-weight: bold;
        }

        .fc .fc-daygrid-day-top {
            padding: 5px; 
        }
        #activityForm {
            display: none; 
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .delete-btn {
            cursor: pointer;
            font-size: 0.6em; 
            position: absolute; 
            top: 5px; 
            right: 5px; 
            color: red; 
            z-index: 10; 
        }
        .fc-event {
            position: relative; 
            padding: 10px;
        }
        .summary-widget {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            margin-bottom: 20px;
        }
        .widget {
            flex: 1;
            margin: 10px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            font-size: 1.2rem;
        }
        .chart-container {
            margin: 20px 0;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
        }
        .dataTable {
            margin-top: 20px;
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

        fieldset {
        border: 2px solid #1a237e; /* Navy Blue Border */
        border-radius: 10px;
        margin-bottom: 20px;
        padding: 20px;
        }

        legend {
            font-size: 24px;
            font-weight: bold;
            color: #1a237e; /* Navy Blue Text */
            border-bottom: 2px solid #1a237e; /* Navy Blue Bottom Border */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>

</head>

<body>
    <?php require_once("support/header.php")?>
    <?php require_once("support/sidebar.php")?>


    <main id="main" class="main mt-5">
        <section class="section">
            <div class="container">
                
                
                <!-- Ungraded Students Table -->
                <!-- <div class="card">
                    <div class="card-body">
                        <table id="ungradedTable" class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Program</th>
                            <th class="">Section</th>
                            <th>Subjects</th>
                            <th class="text-center">Ungraded Counts</th>
                            <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        </table>
                    </div>
                </div> -->


            <!-- <div class="modal fade" id="viewStudentModal" tabindex="-1" role="dialog" aria-labelledby="viewStudentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <fieldset class="border p-4 rounded mb-4">
                                <legend>Students Not Yet Graded</legend>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <h6 class="custom-card-title">
                                                <i class="bi bi-book me-2"></i> <span class="fw-bold" id="subjectTitle"></span>
                                                </h6>
                                            </div>
                                        </div>
                                        <table class="table datatable table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">LRN</th>
                                                    <th>Name</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">.</td>
                                                    <td class="text-center"></td>
                                                    <td></td>
                                                    <td class="text-center"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary" name="cancelBtn" data-bs-dismiss="modal">
                                        <i class="bi bi-x"></i> Cancel
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
            </div> -->


                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="card-title mb-3 fs-4">Calendar of Activities</h5>
                        <div id="calendar" style="width:100%; height:500px;"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>




    <!-- Vendor JS Files -->
    <script src="../gmsn/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../gmsn/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../gmsn/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../gmsn/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../gmsn/assets/vendor/chart.js/chart.umd.js"></script>

    <!-- Template Main JS File -->
    <script src="../gmsn/assets/js/main.js"></script>
    <script src="../gmsn/assets/calendar-main.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable for Ungraded Students Table
    const dataTable = new simpleDatatables.DataTable("#ungradedTable");

    // Fetch ungraded students data from the server
    fetch('../gmsn/fetch/assigned_subjects.php')
        .then(response => response.json())
        .then(data => {
            if (data.subjects && data.subjects.length > 0) {
                populateTable(data.subjects); // Pass subjects array for table
            } else {
                console.error("No ungraded data found.");
            }
        })
        .catch(error => console.error('Error fetching ungraded data:', error));

    // Function to capitalize the first letter of each word
    function ucwords(str) {
        return str.replace(/\b\w/g, function(match) {
            return match.toUpperCase();
        });
    }

    // Convert string to lowercase
    function strtolower(str) {
        return str.toLowerCase();
    }

    // Populate the ungraded students table with fetched data
    function populateTable(subjects) {
        const tableBody = document.querySelector('#ungradedTable tbody');
        let count = 1;

        tableBody.innerHTML = '';  // Clear any previous data

        subjects.forEach(subject => {
            if (subject.ungraded_students && subject.ungraded_students.length > 0) {
                subject.ungraded_students.forEach(student => {
                    // Handle null values in student data gracefully
                    const programCode = student.programcode ? student.programcode.toUpperCase() : '-';
                    const sectionName = student.secName ? ucwords(strtolower(student.secName)) : 'N/A';

                    const row = `
                        <tr>
                            <td class="text-center">${count++}.</td>
                            <td class="text-center">${programCode}</td>
                            <td class="text-left">${student.gradelvlcode} - ${sectionName}</td>
                            <td class="text-left" style="white-space: nowrap">${subject.subjectName}</td>
                            <td>${subject.ungraded_count}</td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm view-student-btn"
                                    data-bs-toggle="modal" data-bs-target="#viewStudentModal" 
                                    data-subject-id="${subject.subjectID}" 
                                    data-subject-name="${subject.subjectName}" 
                                    data-faculty-id="${student.facultyAssignID}" 
                                    data-faculty-assign-id="${student.facultyAssignID}" 
                                    data-section-id="${student.secID}" 
                                    data-dept-id="${student.deptID}">
                                    <i class="bi bi-eye me-1"></i> View
                                </button>
                            </td>
                        </tr>`;
                    tableBody.innerHTML += row;
                });
            }
        });

        // Event listeners to all "View" buttons to open modal with details
        document.querySelectorAll('.view-student-btn').forEach(button => {
            button.addEventListener('click', function() {
                const subjectID = this.getAttribute('data-subject-id');
                const subjectTitle = this.getAttribute('data-subject-name');

                // Populate modal with subject title
                document.getElementById('subjectTitle').textContent = subjectTitle;

                // Fetch ungraded students for the selected subject
                fetchUngradedStudents(subjectID);
            });
        });

        // Refresh the DataTable with the new data
        dataTable.update();  
    }

    // Function to fetch ungraded students for a specific subject
    function fetchUngradedStudents(subjectID) {
        const modalBody = document.querySelector('#viewStudentModal .modal-body tbody');
        modalBody.innerHTML = ''; // Clear previous results

        // Fetch ungraded students for the selected subject
        fetch(`../gmsn/fetch/fetch_ungraded_students.php?subjectID=${subjectID}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.students) {
                    populateModalWithStudents(data.students);
                } else {
                    console.error("No ungraded students found for this subject.");
                    modalBody.innerHTML = `<tr><td colspan="4" class="text-center">No ungraded students found.</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error fetching ungraded students:', error);
                modalBody.innerHTML = `<tr><td colspan="4" class="text-center">Error fetching data.</td></tr>`;
            });
    }

    // Populate the modal with ungraded students' details
    function populateModalWithStudents(students) {
        const modalBody = document.querySelector('#viewStudentModal .modal-body tbody');
        students.forEach((student, index) => {
            const row = `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td class="text-center">${student.lrn}</td>
                    <td>${student.name}</td>
                    <td class="text-center">
                        <button class="btn btn-success btn-sm">Grade</button>
                    </td>
                </tr>`;
            modalBody.innerHTML += row;
        });
    }
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '../gmsn/fetch/fetch_events.php',
        selectable: false, 
        editable: false,   
        droppable: false,  

        eventClick: function(info) {
            Swal.fire({
                title: `<strong>${info.event.title}</strong>`, 
                html: `
                    <p style="text-align: justify; text-justify: inter-word;">
                        ${info.event.extendedProps.description || "No description available."}
                    </p>
                `, 
                icon: 'info', 
                confirmButtonText: 'Close'
            });
        },
    });

    calendar.render();
});
</script>


<script src="assets/sweetalert2.all.min.js"></script>

<?php
require_once "includes/config.php";

if($_SESSION['isDefault'] == 0){
    if (isset($_GET['login']) && $_GET['login'] == 'success' && isset($_SESSION['userID']) && isset($_SESSION['userTypeID'])) {
        if ($_SESSION['userTypeID'] == 2) {
            $userID = $_SESSION['userID'];
    
            $sql = "SELECT fname, userTypeID FROM users WHERE userID = :userID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->execute();
    
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $adminName = $user['fname'];
                $role = ($user['userTypeID'] == 1) ? 'Admin' : 'Faculty';
            } else {
                $adminName = 'Faculty'; 
                $role = 'Faculty'; 
            }
        }
    
        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Login Successful",
                text: "Welcome, ' . htmlspecialchars($adminName) . '! You are logged in as ' . htmlspecialchars($role) . '.",
                showConfirmButton: false,
                timer: 3000
            }).then(() => {
                window.history.replaceState(null, null, window.location.pathname);
            });
        </script>';
    
    }
}

?>
</body>

</html>
