<!-- CSS -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css"> -->

<!-- JavaScript -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script> -->

<style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .form-control {
        border: none;
        border-radius: 0;
        border-bottom: 1px solid #ced4da;
        box-shadow: none;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #1a237e; /* Navy Blue Focus Color */
        box-shadow: 0 0 0 0.25rem rgba(26, 35, 126, 0.25); /* Navy Blue Shadow on Focus */
    }

    .header h1 {
        margin: 0;
        font-size: 32px;
        font-weight: bold;
    }

    .header p {
        margin-top: 10px;
        font-size: 18px;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
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

    .btn-primary {
        background-color: #1a237e; /* Navy Blue Button Color */
        border-color: #1a237e; /* Navy Blue Border Color */
    }

    .btn-primary:hover {
        background-color: #0d47a1; /* Darker Navy Blue on Hover */
        border-color: #0d47a1;
    }



    .btn-secondary:hover {
        background-color: #bdbdbd; /* Darker Gray on Hover */
        border-color: #bdbdbd;
    }
    .text-primary {
    font-weight: bold;
}

</style>


<?php 
    require_once("includes/config.php");
    $query = "SELECT ay.*, s.semName FROM academic_year ay
    JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($acadYear as $row): 
        endforeach 
?>
<!-- Modal Form subject student-->
<div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <form action="modals/enroll_student.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Enroll Students</legend>
                        <input type="hidden" name="subjectID" value="<?php echo $subID?>">
                        <input type="hidden" name="programID" value="<?php echo $progID?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID?>">
                        <input type="hidden" name="facultyID" value="<?php echo $facultyID?>">
                        <input type="hidden" name="secID" value="<?php echo $secID?>">
                        <input type="hidden" name="facultyName" value="<?php echo $facultyName?>">
                        <input type="hidden" name="secName" value="<?php echo $secName?>">
                        <input type="hidden" name="subjectName" value="<?php echo $subjectName?>">
                        <input type="hidden" name="semID" value="<?php echo $semID?>">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="" readonly value="<?php echo $row['ayName']; ?>">
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                </div>
                                    <input type="text" class="form-control" id="selsem" name="" readonly value="<?php echo $row['semName']; ?>">
                                    <input type="hidden" name="selSem" value="<?php echo $row['semID']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selstud" class="form-label fw-bold">Select Student:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" multiple data-live-search="true" id="selstud" name="selStud[]" required>
                                       <?php
                                        include("../includes/config.php");
                                            try {
                                                $sql = "SELECT * FROM students WHERE isActive = 1 AND studID NOT IN (
                                                    SELECT studID FROM section_students WHERE secID = $secID)";
                                                $stmt = $conn->prepare($sql);
                                               $stmt->execute();
                                                // Output the select options
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row['studID'] . '">' . $row['lname'] .', '.$row['fname'].' '.$row['mname']. '</option>';
                                                }
                                            } catch (PDOException $e) {
                                                // Handle any errors that occur during query execution
                                                echo '<option disabled>Error fetching subjects</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div id="selectedStud" class="fw-bold">
                                Selected Students:
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="enrollBtn">
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
    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        // Event listener for changes in the select element
        $('#selstud').change(function() {
            // Clear previous selections
            $('#selectedStud').empty();

            // Get selected options
            var selectedOptions = $('#selstud').find(":selected");

            // Display selected options below the select element
            if (selectedOptions && selectedOptions.length > 0) {
                $('#selectedStud').append('<ul>');
                selectedOptions.each(function() {
                    $('#selectedStud ul').append('<li>' + $(this).text() + '</li>');
                });
                $('#selectedStud').append('</ul>');
            } else {
                $('#selectedStud').append('<ul><li>None</li></ul>'); // Add 'None' if no prerequisites selected
            }
        });
    });
</script>





<?php 
    require_once("includes/config.php");
    $query = "SELECT ay.*, s.semName FROM academic_year ay
    JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($acadYear as $row): 
        endforeach 
?>
<!-- Modal Form section student-->
<div class="modal fade" id="addStudentSec" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <form action="modals/enroll_studentSec.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Enroll Students</legend>
                        <input type="hidden" name="programID" value="<?php echo $programID?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID?>">
                        <input type="hidden" name="secID" value="<?php echo $secID?>">
                        <input type="hidden" name="secName" value="<?php echo $secName?>">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="" readonly value="<?php echo $row['ayName']; ?>">
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                </div>
                                    <input type="text" class="form-control" id="selsem" name="" readonly value="<?php echo $row['semName']; ?>">
                                    <input type="hidden" name="selSem" value="<?php echo $row['semID']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selstud" class="form-label fw-bold">Select Student:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" multiple data-live-search="true" id="selstud" name="selStud[]" required>
                                       <?php
                                        include("../includes/config.php");
                                            try {
                                                $sql = "SELECT * FROM students WHERE isActive = 1 AND studID NOT IN (
                                                    SELECT studID FROM section_students WHERE secID = $secID)";
                                                $stmt = $conn->prepare($sql);
                                               $stmt->execute();
                                                // Output the select options
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row['studID'] . '">' . $row['lname'] .', '.$row['fname'].' '.$row['mname']. '</option>';
                                                }
                                            } catch (PDOException $e) {
                                                // Handle any errors that occur during query execution
                                                echo '<option disabled>Error fetching subjects</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div id="selectedStud" class="fw-bold">
                                Selected Students:
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="enrollSecBtn">
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
    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        // Event listener for changes in the select element
        $('#selstud').change(function() {
            // Clear previous selections
            $('#selectedStud').empty();

            // Get selected options
            var selectedOptions = $('#selstud').find(":selected");

            // Display selected options below the select element
            if (selectedOptions && selectedOptions.length > 0) {
                $('#selectedStud').append('<ul>');
                selectedOptions.each(function() {
                    $('#selectedStud ul').append('<li>' + $(this).text() + '</li>');
                });
                $('#selectedStud').append('</ul>');
            } else {
                $('#selectedStud').append('<ul><li>None</li></ul>'); // Add 'None' if no prerequisites selected
            }
        });
    });
</script>



<?php 
require_once("includes/config.php");
$query = "SELECT ay.*, s.semName FROM academic_year ay
JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
$stmt = $conn->prepare($query);
$stmt->execute();
$acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
foreach ($acadYear as $row): 
endforeach 
?>
<!-- Modal Form enroll subjects-->
<div class="modal fade" id="enrollSubjects" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="modals/enroll_studentSubject.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Enroll Subjects</legend>
                        <input type="hidden" name="studID" value="<?php echo $studID; ?>">
                        <input type="hidden" name="semID" value="<?php echo $semID; ?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID; ?>">
                        <input type="hidden" name="programID" value="<?php echo $programID; ?>">
                        <input type="hidden" name="secID" value="<?php echo $secID; ?>">
                        <input type="hidden" name="ayID" value="<?php echo $ayID; ?>">
                        <input type="hidden" name="secName" value="<?php echo $secName; ?>">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="" readonly value="<?php echo $row['ayName']; ?>">
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selsem" name="" readonly value="<?php echo $row['semName']; ?>">
                                    <input type="hidden" name="selSem" value="<?php echo $row['semID']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selSub" class="form-label fw-bold">Select Subjects:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" multiple data-live-search="true" id="selSub" name="selSub[]" required>
                                        <?php
                                        include("../includes/config.php");
                                        try {
                                            $sql = "SELECT c.subjectID, s.subjectname 
                                                    FROM curriculum c
                                                    JOIN subjects s ON s.subjectID = c.subjectID 
                                                    LEFT JOIN section_students ss ON c.subjectID = ss.subjectID AND ss.studID = :studID
                                                    WHERE c.isActive = 1 
                                                        AND c.semID = :semID 
                                                        AND c.gradelvlID = :gradelvlID 
                                                        AND c.programID = :programID
                                                        AND ss.subjectID IS NULL";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                                            $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                            $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
                                            $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                                            $stmt->execute();

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['subjectID'] . '">' . $row['subjectname'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching subjects</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div id="selectedSub" class="fw-bold">
                                Selected Subjects:
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="enrollSubBtn">
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
    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        $('#selSub').change(function() {
            $('#selectedSub').empty();
            var selectedOptions = $('#selSub').find(":selected");
            if (selectedOptions && selectedOptions.length > 0) {
                $('#selectedSub').append('<ul>');
                selectedOptions.each(function() {
                    $('#selectedSub ul').append('<li>' + $(this).text() + '</li>');
                });
                $('#selectedSub').append('</ul>');
            } else {
                $('#selectedSub').append('<ul><li>None</li></ul>');
            }
        });
    });
</script>

<?php 
    require_once("includes/config.php");
    $query = "SELECT ay.*, s.semName FROM academic_year ay
    JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($acadYear as $row): 
        endforeach 
?>
<!-- Modal Form for sections students -->
<div class="modal fade" id="addStudentsection" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <form action="modals/enroll_section.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Enroll Students</legend>
                        <input type="hidden" name="programID" value="<?php echo $programID?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID?>">
                        <input type="hidden" name="secID" value="<?php echo $secID?>">
                        <input type="hidden" name="secName" value="<?php echo $secName?>">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="" readonly value="<?php echo $row['ayName']; ?>">
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                </div>
                                    <input type="text" class="form-control" id="selsem" name="" readonly value="<?php echo $row['semName']; ?>">
                                    <input type="hidden" name="selSem" value="<?php echo $row['semID']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selstud2" class="form-label fw-bold">Select Student:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" multiple data-live-search="true" id="selstud2" name="selStud[]" required>
                                       <?php
                                        include("../includes/config.php");
                                            try {
                                                $sql = "SELECT * FROM students WHERE isActive = 1";
                                                $stmt = $conn->prepare($sql);
                                               $stmt->execute();
                                                // Output the select options
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row['studID'] . '">' . $row['lname'] .', '.$row['fname'].' '.$row['mname']. '</option>';
                                                }
                                            } catch (PDOException $e) {
                                                // Handle any errors that occur during query execution
                                                echo '<option disabled>Error fetching subjects</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div id="selectedStud2" class="fw-bold">
                                Selected Students:
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="enrollSectionBtn">
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
    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        // Event listener for changes in the select element
        $('#selstud2').change(function() {
            // Clear previous selections
            $('#selectedStud2').empty();

            // Get selected options
            var selectedOptions = $('#selstud2').find(":selected");

            // Display selected options below the select element
            if (selectedOptions && selectedOptions.length > 0) {
                $('#selectedStud2').append('<ul>');
                selectedOptions.each(function() {
                    $('#selectedStud2 ul').append('<li>' + $(this).text() + '</li>');
                });
                $('#selectedStud2').append('</ul>');
            } else {
                $('#selectedStud2').append('<ul><li>None</li></ul>'); // Add 'None' if no prerequisites selected
            }
        });
    });
</script>

<!-- grade entry for subjects in section -->
<div class="modal fade" id="inputGradeModalSec" tabindex="-1" role="dialog" aria-labelledby="inputGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <?php include("includes/config.php");
                 foreach ($curriculum as $row):
                endforeach;
            ?>
            <form action="save_grade.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                    <input type="hidden" id="enrollID" name="enrollID">
                    <input type="hidden" id="subjectID" name="subjectID">
                    <input type="hidden" id="studID" name="studID">
                    <input type="hidden" id="semID" name="semID" value="<?php echo $row['semID']; ?>">
                    <input type="hidden" id="gradelvlID" name="gradelvlID" value="<?php echo $gradelvlID ?>">
                    <input type="hidden" id="programID" name="programID" value="<?php echo $row['programID']; ?>">
                    <input type="hidden" id="ayID" name="ayID" value="<?php echo $row['ayID']; ?>">
                    <input type="hidden" id="subjectNameSec" name="subjectName">
                    <input type="hidden" id="studname" name="studname" value="<?php echo $row['studname']; ?>">
                    
                    <legend class="mb-4" id="subjectNameLegend"></legend>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold" style="color: #1a237e">Name: <?php echo $row['studname']; ?></h6>
                                <h6 class="fw-bold" style="color: #616161">LRN: <?php echo $row['lrn']; ?></h>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <h6 class="fw-bold">Mark as: </h6>
                            </div>
                            <div class="col-md-7">
                                <input type="checkbox" name="inc" id="inc" onclick="handleCheckbox(this)">
                                <label for="inc" class="fw-bold mr-4">Incomplete</label>

                                <input type="checkbox" name="drp" id="drp" onclick="handleCheckbox(this)">
                                <label for="drp" class="fw-bold">Dropped</label>
                            </div>
                        </div>
                        <script>
                            function handleCheckbox(checkbox) {
                                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                                checkboxes.forEach(function(element) {
                                    if (element !== checkbox) {
                                        element.checked = false;
                                    }
                                });
                            }
                        </script>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="txtgradeSub" class="fw-bold"><?php echo ($row['semID'] == 1) ? '1st Quarter Grade: ' : '3rd Quarter: ' ?></label>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    </div>
                                    <?php 
                                        $query = "SELECT * FROM gradepermission";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $permit = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                                        foreach ($permit as $gradePermit): 
                                            endforeach 
                                    ?>
                                    <input type="number" id="txtgradeSub" name="txtGrade" placeholder="Enter Grade" class="form-control" step="0.01" max="100" <?php echo($gradePermit['_first'] == 0)? 'readonly' : ''?>>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="txtgradeSub2" class="fw-bold"><?php echo ($row['semID'] == 1) ? '2nd Quarter Grade: ' : '4th Quarter Grade: ' ?></label>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    </div>
                                    <input type="number" id="txtgradeSub2" name="txtGrade2" placeholder="Enter Grade" class="form-control" step="0.01" max="100" <?php echo($gradePermit['_second'] == 0)? 'readonly' : ''?>>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveGradeSubBtn">
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
$(document).ready(function(){
    $('.grade-btn').click(function(){
        var enrollID = $(this).data('enroll-id');
        var studID = $(this).data('stud-id');
        var subjectID = $(this).data('subject-id');
        var subjectName = $(this).data('subject-name');
        var first = $(this).data('grade-first');
        var second = $(this).data('grade-second');

        $('#enrollID').val(enrollID);
        $('#studID').val(studID);
        $('#subjectID').val(subjectID);
        $('#subjectNameSec').val(subjectName);
        $('#subjectNameLegend').text(subjectName);
        $('#txtgradeSub').val(first);
        $('#txtgradeSub2').val(second);
    });
});

</script>   



<!-- grade entry for subjects in faculty -->
<div class="modal fade" id="inputGradeModalSub" tabindex="-1" role="dialog" aria-labelledby="inputGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <?php include("includes/config.php");
                 foreach ($curriculum as $row):
                endforeach;
            ?>
            <form action="save_grade.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                    <input type="hidden" id="enrollIDSub" name="enrollID">
                    <input type="hidden" id="subjectIDSub" name="subjectID">
                    <input type="hidden" id="studIDSub" name="studID">
                    <input type="hidden" id="semIDSub" name="semID" value="<?php echo $row['semID']; ?>">
                    <input type="hidden" id="gradelvlIDSub" name="gradelvlID" value="<?php echo $gradelvlID ?>">
                    <input type="hidden" id="programIDSub" name="programID" value="<?php echo $row['programID']; ?>">
                    <input type="hidden" id="subjectNameSecSub" name="subjectName">
                    <input type="hidden" id="secIDSub" name="secID">
                    <input type="hidden" id="secNameSub" name="secName">
                    <input type="hidden" id="facultyIDSub" name="facultyID">
                    <input type="hidden" id="facultyNameSub" name="facultyName">
                    <input type="hidden" id="ayIDSub" name="ayID">
                    
                    <legend class="mb-4" id="subjectNameLegend"><?php echo $subjectName ?></legend>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold" style="color: #1a237e">Name: <?php echo $row['studname']; ?></h6>
                                <h6 class="fw-bold" style="color: #616161">LRN: <?php echo $row['lrn']; ?></h>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <h6 class="fw-bold">Mark as: </h6>
                            </div>
                            <div class="col-md-7">
                                <input type="checkbox" name="inc" id="inc2" onclick="handleCheckbox(this)">
                                <label for="inc2" class="fw-bold mr-4">Incomplete</label>

                                <input type="checkbox" name="drp" id="drp2" onclick="handleCheckbox(this)">
                                <label for="drp2" class="fw-bold">Dropped</label>
                            </div>
                        </div>
                        <script>
                            function handleCheckbox(checkbox) {
                                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                                checkboxes.forEach(function(element) {
                                    if (element !== checkbox) {
                                        element.checked = false;
                                    }
                                });
                            }
                        </script>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="txtgradeSubSub" class="fw-bold"><?php echo ($row['semID'] == 1) ? '1st Quarter Grade: ' : '3rd Quarter Grade: ';?></label>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    </div>
                                    <input type="number" id="txtgradeSubSub" name="txtGrade" placeholder="Enter Grade" class="form-control" step="0.01" max="100" <?php echo ($gradePermit['_first'] == 0) ? 'readonly' : ''?>>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                    <label for="txtgradeSub2" class="fw-bold"><?php echo ($row['semID'] == 1) ? '2nd Quarter Grade: ' : '4th Quarter Grade: ';?></label>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    </div>
                                    <input type="number" id="txtgradeSubSub2" name="txtGrade2" placeholder="Enter Grade" class="form-control" step="0.01" max="100" <?php echo($gradePermit['_second'] == 0) ? 'readonly' : ''?>>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveGradeSubSubBtn">
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
$(document).ready(function(){
    $('.grade-btn').click(function(){
        var enrollID = $(this).data('enroll-id');
        var studID = $(this).data('stud-id');
        var subjectID = $(this).data('subject-id');
        var subjectName = $(this).data('subject-name');
        var first = $(this).data('grade-first');
        var second = $(this).data('grade-second');
        var secID = $(this).data('sec-id');
        var secName = $(this).data('sec-name');
        var facultyID = $(this).data('fac-id');
        var facultyName = $(this).data('fac-name');
        var ayID = $(this).data('session-id');

        $('#enrollIDSub').val(enrollID);
        $('#studIDSub').val(studID);
        $('#subjectIDSub').val(subjectID);
        $('#subjectNameSecSub').val(subjectName);
        $('#subjectNameLegendSub').text(subjectName);
        if (first !== 0) {
            $('#txtgradeSubSub').val(first);
        }

        if (second !== 0) {
            $('#txtgradeSubSub2').val(second);
        }
        $('#secIDSub').val(secID);
        $('#secNameSub').val(secName);
        $('#facultyIDSub').val(facultyID);
        $('#facultyNameSub').val(facultyName);
        $('#ayIDSub').val(ayID);
    });
});

</script>   



<!-- Modal for Promoting Students -->
<div class="modal fade" id="promoteStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true" data-stud-ids="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="modals/enroll_student.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Promote Students</legend>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selstud" class="form-label fw-bold">Select Section:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" data-live-search="true" id="selsec" name="selSec" required>
                                        <?php
                                        include("../includes/config.php");
                                        try {
                                            $sql = "SELECT s.*, 
                                                (SELECT programcode FROM programs WHERE programID = s.programID) AS programname,
                                                (SELECT gradelvl FROM grade_level WHERE gradelvlID = s.gradelvlID) AS gradelvl,
                                                (SELECT ayName FROM academic_year WHERE ayID = s.ayID) AS ay,
                                                (SELECT semName FROM semester WHERE semID = s.semID) AS sem 
                                            FROM sections s
                                            ORDER BY ay DESC";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                            // Output the select options
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['secID'] . '" data-studid="' . $row['studID'] . '">' . $row['programname'] . ' ' . $row['gradelvl'] . ' - ' . $row['secName'] . ' (' . $row['ay'] . ' - ' . $row['sem'] . ') ' . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            // Handle any errors that occur during query execution
                                            echo '<option disabled>Error fetching sections</option>';
                                        }
                                        ?>
                                    </select>
                                    </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="promoteBtn">
                            <i class="bi bi-check-circle"></i> Promote
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
    document.getElementById('promoteAllBtn').addEventListener('click', function(event) {
        event.preventDefault();
        
        var studIDs = [];
        
        // Iterate through each row in the table
        document.querySelectorAll('.datatable tbody tr').forEach(function(row) {
            var studID = row.getAttribute('data-stud-id'); // Adjust this based on your actual data attribute
            studIDs.push(studID);
        });
        
        // Pass studIDs to the modal for further processing
        document.getElementById('promoteStudentModal').setAttribute('data-stud-ids', JSON.stringify(studIDs));
        
        // Populate the select options dynamically
        var selectElement = document.getElementById('selsec');
        selectElement.innerHTML = ''; // Clear previous options
        
        // Fetch sections dynamically and populate the select
        fetchSections(selectElement); // You need to implement this function to populate the select options
    });
});

// Function to fetch sections and populate the select
function fetchSections(selectElement) {
    // Example AJAX or fetch request to fetch sections
    fetch('your_endpoint_to_fetch_sections.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(section => {
                var option = document.createElement('option');
                option.value = section.secID;
                option.textContent = section.programname + ' ' + section.gradelvl + ' - ' + section.secName + ' (' + section.ay + ' - ' + section.sem + ')';
                selectElement.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching sections:', error);
        });
}

</script>