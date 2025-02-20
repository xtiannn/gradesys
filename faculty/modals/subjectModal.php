
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
</style>

<!-- Modal Form -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_faculty2.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Assign Faculty</legend>
                        <!-- Hidden input field to store curriculumID -->
                        <input type="hidden" id="curriculumIDInput" name="curriculumID">                        
                        <input type="hidden" name="programID" value="<?php echo isset($_GET['programID']) ? $_GET['programID'] : ''; ?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo isset($_GET['gradelvlID']) ? $_GET['gradelvlID'] : ''; ?>">
                        <input type="hidden" name="semID" value="<?php echo isset($_GET['semID']) ? $_GET['semID'] : ''; ?>">
                        <input type="hidden" name="secID" value="<?php echo isset($_GET['secID']) ? $_GET['secID'] : ''; ?>">
                        <input type="hidden" name="secName" value="<?php echo isset($_GET['secName']) ? $_GET['secName'] : ''; ?>">
                        <input type="hidden" id="subjectIDInput" name="subjectID" value="<?php echo $row['subjectID'] ?>">

                        <!-- Select faculty dropdown -->
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                            <label for="selfac" class="form-label fw-bold">Grade Level:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-book"></i></span>
                                    </div>
                                <select class="form-select form-control selectpicker" id="selfac" name="selFaculty" data-live-search="true" required>
                                    <option selected disabled>Select Faculty</option>
                                    <?php
                                    require_once("./includes/config.php");
                                    try {
                                        $query = "SELECT * FROM faculty WHERE isActive=1";

                                        $stmt = $conn->query($query);

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row['facultyID'] . '">'  . $row['lname'] .', '.$row['fname'].' '.$row['mname'] . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<option disabled>Error fetching faculty</option>';
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label for="txtday" class="form-label fw-bold">Day:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select name="txtDay[]" id="txtday" class="form-control selectpicker" multiple>
                                        <option value="Mon">Monday</option>
                                        <option value="Tue">Tuesday</option>
                                        <option value="Wed">Wednesday</option>
                                        <option value="Thu">Thursday</option>
                                        <option value="Fri">Friday</option>
                                        <option value="Sat">Saturday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="start-time" class="form-label fw-bold">Start Time:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    </div>
                                    <input type="time" class="form-control" id="start-time" name="txtStartTime" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end-time" class="form-label fw-bold">End Time:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    </div>
                                    <input type="time" class="form-control" id="end-time" name="txtEndTime" required>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <!-- Form submission buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveSubjectBtn">
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
$('#addSubjectModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); 
    var currID = button.data('curriculum-id'); 
    $('#curriculumIDInput').val(currID);
});

$('#inputGradeModal').on('hidden.bs.modal', function() {
    $('#curriculumIDInput').val('');
});

$('#gradeForm').submit(function(event) {
    event.preventDefault();
    
    var currID = $('#curriculumIDInput').val();
    
});

</script>