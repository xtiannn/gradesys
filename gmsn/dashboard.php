<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "includes/config.php";

if (!isset($_SESSION['userID'])) {    
    header("Location: /cap/index.php");
    exit;
}



// Fetch total number of students
try {
    $sql_student = "SELECT COUNT(*) as total_student FROM students";
    $stmt = $conn->prepare($sql_student);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_student = $result[0]['total_student'];
} catch (PDOException $e) {
    $total_student = 0; // Default to 0 on error
    echo "<span class='ms-3'>Error: " . $e->getMessage() . "</span>";
}

// Fetch the number of students by program
$programData = [];
$programCounts = [];

try {
    $sql_program = "
        SELECT 
            p.programID, 
            p.programcode, 
            COUNT(s.programID) AS student_count
        FROM 
            programs p
        LEFT JOIN 
            section_students s ON p.programID = s.programID 
            AND s.ayName = (SELECT ayName FROM academic_year)
        WHERE
            p.isActive = 1
        AND
            p.programcode != 'Elem/JHS'
        AND
            s.subjectID IS NULL  
        GROUP BY 
            p.programID, 
            p.programcode;

    ";
    $stmt = $conn->prepare($sql_program);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $programData[] = $row['programcode'];
        $programCounts[] = $row['student_count'];
    }
} catch (PDOException $e) {
    echo "<span class='ms-3'>Error: " . $e->getMessage() . "</span>";
}


$yearData = [];
$studentCount = [];

try {
    $sql = "
        SELECT 
            s.ayName AS academic_year, 
            COUNT(DISTINCT s.studID) AS student_count 
        FROM 
            section_students s
        GROUP BY 
            s.ayName
        ORDER BY 
            s.ayName;

    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $yearData[] = $row['academic_year'];
        $studentCount[] = $row['student_count'];
    }
} catch (PDOException $e) {
    echo "<span class='ms-3'>Error: " . $e->getMessage() . "</span>";
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
    <link href="assets/img/gmsnlogo.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="assets/google-fonts.css" rel="stylesheet">

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

    <!-- Chart.js -->
    <script src="assets/cdn-jsdelivr-net-npm-chart.js"></script>

    <link href="assets/cdn-jsdelivr-net-npm-fullcalendar@5.11.3-main.min.css" rel="stylesheet" />

    <script type="text/javascript">
        function preventBack() {window.history.forward()};
        setTimeout("preventBack()", 0);
            window.onunload=function(){null;}
  </script>

    <style>
        .card {
            height: auto; 
            width: auto;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between; 
            padding: 20px;
        }

        .card-content {
            display: flex;
            align-items: center;
        }

        .card-content span {
            font-family: 'Poppins', sans-serif;
            font-size: 1.3rem; 
            font-weight: bold;
            color: #fff;
            margin-left: 10px; 
        }

        .card i {
            font-size: 2.5rem;
            color: #fff;
            transition: transform 0.3s ease;
        }

        /* Chart container */
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



    </style>

</head>

<body>
    <?php require_once("support/header.php")?>
    <?php require_once("support/sidebar.php")?>

    <main id="main" class="main mt-0">
        <section class="section">
            <div class="container">
                <div class="col-lg-12">         
                    <div class="row">
                        <!-- Existing Cards -->
                        <div class="col-lg-3 col-md-6">
                            <a href="manage_studentsRec.php" style="text-decoration: none; color: inherit;">
                                <div class="card bg-primary">
                                    <div class="card-body">
                                        <div class="card-content">
                                            <i class="bi bi-people fs-6" ></i>
                                            <span class="fs-6 fw-normal"><?php echo htmlspecialchars($total_student); ?></span>
                                            <span class='text-white fw-bold-6 ms-1' style="white-space: nowrap; font-size: 15px">Active Students</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="faculty.php" style="text-decoration: none; color: inherit;">
                                <div class="card bg-secondary">
                                    <div class="card-body">
                                        <div class="card-content">
                                            <i class="bi bi-people fs-6"></i>
                                            <?php
                                            try {
                                                $sql_faculty = "SELECT COUNT(*) as total_faculty FROM faculty WHERE isActive = 1";
                                                $stmt = $conn->prepare($sql_faculty);
                                                $stmt->execute();
                                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $total_faculty = $result[0]['total_faculty'];
                                                echo "<span class='fs-6 fw-normal'>$total_faculty</span>";
                                                echo "<span class='text-white fw-bold ms-1' style='white-space: nowrap; font-size: 15px'>Active Faculties</span> ";
                                            } catch (PDOException $e) {
                                                echo "<span>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="subjects.php" style="text-decoration: none; color: inherit;">
                                <div class="card bg-info">
                                    <div class="card-body">
                                        <div class="card-content">
                                            <i class="bi bi-book fs-6"></i>
                                            <?php 
                                            try {
                                                $sql_subject = "SELECT COUNT(*) as total_subject FROM subjects WHERE isActive = 1";
                                                $stmt = $conn->prepare($sql_subject);
                                                $stmt->execute();
                                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $total_subject = $result[0]['total_subject'];
                                                echo "<span class='fs-6 fw-normal'>$total_subject</span>";
                                                echo "<span class='text-white fw-bold ms-1' style='white-space: nowrap; font-size: 15px'>Active Subjects</span>";
                                            } catch (PDOException $e) {
                                                echo "<span>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="programs.php" style="text-decoration: none; color: inherit;">
                                <div class="card bg-success">
                                    <div class="card-body">
                                        <div class="card-content">
                                            <i class="bi bi-calendar2-check fs-6"></i>
                                            <?php 
                                            try {
                                                $sql_program = "SELECT COUNT(*) as total_program FROM programs WHERE isActive=1";
                                                $stmt = $conn->prepare($sql_program);
                                                $stmt->execute();
                                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $total_program = $result[0]['total_program'];
                                                echo "<span class='fs-6 fw-normal'>$total_program</span>";
                                                echo "<span class='text-white fw-bold ms-1' style='white-space: nowrap; font-size: 15px'>Active Programs</span>";
                                            } catch (PDOException $e) {
                                                echo "<span>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-3">
                        <h5 class="card-title mb-3 fs-4">Program Enrollment Overview</h5>
                            <div class="card pt-3" style="height: 300px">
                                <div class="chart-container">
                                    <canvas id="studentsByProgramChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <h5 class="card-title mb-3 fs-4">Annual Enrollment Overview</h5>
                            <div class="card pt-3" style="height: 300px">
                                <div class="chart-container">
                                    <canvas id="activeStudentsLineGraph"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="card-title mb-3 fs-4">Activity Calendar</h5>
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>

        </section>
    </main>



    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/chart.js/chart.min.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/cdn.jsdelivr.net-npm-fullcalendar@5.11.3-main.min.js"></script>



    <script>
        var programData = <?php echo json_encode($programData); ?>;
        var programCounts = <?php echo json_encode($programCounts); ?>;
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Students by Program Pie Chart
    var ctx = document.getElementById('studentsByProgramChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: programData,
            datasets: [{
                data: programCounts,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#4BC0C0',
                    '#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'
                ],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            var label = tooltipItem.label || '';
                            var value = tooltipItem.raw || 0;
                            return label + ': ' + value;
                        }
                    }
                }
            }
        }
        });

    });
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('activeStudentsLineGraph').getContext('2d');
    new Chart(ctx, {
        type: 'line',  // Line chart type
        data: {
            labels: <?php echo json_encode($yearData); ?>,  // Academic years
            datasets: [{
                label: 'Number of Enrolled Students',  // Updated Label
                data: <?php echo json_encode($studentCount); ?>,  // Student count per year
                borderColor: '#FF6384',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: true,
                tension: 0.4  // Smoother line curve
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Students Enrolled'  // Improved y-axis label
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Academic Year'  // Improved x-axis label
                    }
                }
            }
        }
    });
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
        events: 'fetch/fetch_events.php',
        eventDidMount: function(info) {
            // Create a delete button for each event
            var deleteBtn = document.createElement('span');
            deleteBtn.innerHTML = '❌'; 
            deleteBtn.className = 'delete-btn';
            deleteBtn.style.cursor = 'pointer';
            deleteBtn.title = 'Delete Event'; 

            deleteBtn.addEventListener('click', function(e) {
                e.stopPropagation(); 
                Swal.fire({
                    title: 'Confirmation Required',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('fetch/delete_event.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id=${info.event.id}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                info.event.remove();
                                Swal.fire('Deleted!', data.message, 'success');
                            } else {
                                Swal.fire('Error!', data.error, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'There was an error: ' + error, 'error');
                        });
                    }
                });
            });

            info.el.appendChild(deleteBtn);
        },
        eventClick: function(info) {
            if (info.event.id) {
                Swal.fire({
                    title: 'Update Event',
                    html: `
                        <input id="swal-input-title" class="swal2-input" placeholder="Title" value="${info.event.title}">
                        <textarea id="swal-input-description" class="swal2-textarea" placeholder="Description">${info.event.extendedProps.description}</textarea>,
                        <input id="swal-input-end-date" class="swal2-input" placeholder="End Date (YYYY-MM-DD)" value="${info.event.end ? info.event.end.toISOString().split('T')[0] : ''}">`,
                    focusConfirm: false,
                    showCancelButton: true, 
                    confirmButtonText: 'Update',
                    preConfirm: () => {
                        return {
                            title: document.getElementById('swal-input-title').value,
                            description: document.getElementById('swal-input-description').value
                        };
                    }
                }).then((result) => {
                    // Check if the modal was confirmed
                    if (result.isConfirmed) {
                        // Only validate title when confirmed
                        if (result.value.title) {
                            fetch('fetch/update_event.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `id=${info.event.id}&title=${encodeURIComponent(result.value.title)}&description=${encodeURIComponent(result.value.description)}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    info.event.setProp('title', result.value.title);
                                    info.event.setExtendedProp('description', result.value.description);
                                    Swal.fire('Updated!', data.message, 'success');
                                } else {
                                    Swal.fire('Error!', data.error, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'There was an error: ' + error, 'error');
                            });
                        } else {
                            // Show error if the title is not valid
                            Swal.fire('Please provide a valid title.');
                        }
                    } else {
                        // Do nothing on cancel
                       
                    }
                });
            } else {
                Swal.fire('No event selected for updating.');
            }

        },
        dateClick: function(info) {
            // Use SweetAlert for creating a new event in one modal
            Swal.fire({
                title: 'Create New Event',
                // value="${info.dateStr}"
                html: `
                    <input id="swal-input-title" class="swal2-input" placeholder="Title">
                    <textarea id="swal-input-description" class="swal2-textarea" placeholder="Description"></textarea>
                    <input id="swal-input-end-date" class="swal2-input" placeholder="End Date (YYYY-MM-DD)" >`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Save',
                preConfirm: () => {
                    return {
                        title: document.getElementById('swal-input-title').value,
                        endDate: document.getElementById('swal-input-end-date').value || info.dateStr, // Default to start date if empty
                        description: document.getElementById('swal-input-description').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value.title) {
                    var startDate = info.dateStr; // Start date from the date clicked
                    var endDate = result.value.endDate; // Get the end date from the input
                    if (new Date(startDate) <= new Date(endDate)) { // Allow the same day
                        fetch('fetch/create_event.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `title=${encodeURIComponent(result.value.title)}&description=${encodeURIComponent(result.value.description)}&start=${startDate}&end=${endDate}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                calendar.addEvent({
                                    id: data.eventId,
                                    title: result.value.title,
                                    start: startDate,
                                    end: endDate,
                                    description: result.value.description
                                });
                                Swal.fire('Success!', data.message, 'success');
                            } else {
                                Swal.fire('Error!', data.error, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'There was an error: ' + error, 'error');
                        });
                    } else {
                        Swal.fire('End date must be the same day or after the start date.');
                    }
                } else if (result.isDismissed) {
                    // Do nothing on cancel; the dialog is simply dismissed
                } else {
                    Swal.fire('Please provide a valid title.'); // Show only if confirmed with input
                }
            });
        }
    });

    calendar.render();
});






</script>





<?php 

echo '<script src="assets/sweetalert2.all.min.js"></script>';


if (isset($_GET['login']) && $_GET['login'] == 'success' && isset($_SESSION['userID']) && isset($_SESSION['userTypeID'])) {
    if ($_SESSION['userTypeID'] == 1) {
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
            $adminName = 'Admin'; 
            $role = 'Admin'; 
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
?>




</body>

</html>
