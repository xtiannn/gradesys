
<!-- <script src="assets/2.1.1-jquery.min.js"></script> -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script> -->
<script src="assets/bootstrap-select.min.js"></script>

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
                        <!-- Hidden input fields to store various IDs -->
                        <input type="hidden" id="curriculumIDInput" name="curriculumID">                        
                        <input type="hidden" name="programID" value="<?php echo htmlspecialchars(isset($_GET['programID']) ? $_GET['programID'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo htmlspecialchars(isset($_GET['gradelvlID']) ? $_GET['gradelvlID'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="semID" value="<?php echo htmlspecialchars(isset($_GET['semID']) ? $_GET['semID'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="secID" value="<?php echo htmlspecialchars(isset($_GET['secID']) ? $_GET['secID'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="secName" value="<?php echo htmlspecialchars(isset($_GET['secName']) ? $_GET['secName'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" id="subjectIDassign" name="subjectID">
                        <input type="hidden" id="subjectName" name="subjectName">
                        <input type="hidden" id="deptID" name="deptID">
                        <input type="hidden" id="ay_ID" name="ayID">
                        <input type="hidden" id="ayName" name="ayName" value="<?php echo $ayName?>">
                        
                        <!-- Select faculty dropdown -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selfac" class="form-label fw-bold">Select Faculty:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="selfac" name="selFaculty" data-live-search="true" required>
                                        <option selected disabled value="">Select Faculty:</option>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                            $query = "SELECT * FROM faculty WHERE isActive=1";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['facultyID'] . '">' . ucwords(strtolower($row['lname'])) . ', ' . ucwords(mb_strtolower($row['fname'])) .' '. ucwords(strtolower($row['mname'])) . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching faculty</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="txtday" class="form-label fw-bold">Day:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select name="txtDay[]" id="txtday" class="form-control selectpicker" multiple required>
                                        <option value="Mon">Monday</option>
                                        <option value="Tue">Tuesday</option>
                                        <option value="Wed">Wednesday</option>
                                        <option value="Thu">Thursday</option>
                                        <option value="Fri">Friday</option>
                                        <option value="Sat">Saturday</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Time Input Fields -->
                        <div id="timeInputs" class="mb-3"></div>

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
    document.addEventListener('DOMContentLoaded', function() {
        const daySelect = document.getElementById('txtday');
        const timeInputsContainer = document.getElementById('timeInputs');

        function updateTimeInputs() {
            timeInputsContainer.innerHTML = '';

            const selectedDays = Array.from(daySelect.selectedOptions).map(option => option.value);

            selectedDays.forEach(day => {
                const div = document.createElement('div');
                div.className = 'row mb-3';
                div.innerHTML = `
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Start Time (${day}):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            </div>
                            <input type="time" class="form-control" name="txtStartTime[]" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">End Time (${day}):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            </div>
                            <input type="time" class="form-control" name="txtEndTime[]" required>
                        </div>
                    </div>
                `;
                timeInputsContainer.appendChild(div);
            });
        }

        daySelect.addEventListener('change', updateTimeInputs);
    });
</script>




















<!--UPDATE FAC Modal Form FOR ELEM-->
<div class="modal fade" id="UPDATESubjectModalELEM" tabindex="-1" role="dialog" aria-labelledby="UPDATESubjectModalELEMLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_faculty2.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Update Faculty Assignment</legend>
                        <!-- Hidden input fields to store various IDs -->
                        <input type="hidden" id="upcurriculumIDInputElem" name="curriculumID">                        
                        <input type="hidden" id="gradelvlIDElem" name="gradelvlID">
                        <input type="hidden" id="secIDElem" name="secID">
                        <input type="hidden" id="secNameElem" name="secName">

                        <input type="hidden" id="upsubjectIDassignElem" name="subjectID">
                        <input type="hidden" id="upsubjectNameElem" name="subjectName">
                        <input type="hidden" id="updeptIDElem" name="deptID">
                        <input type="hidden" id="upay_IDElem" name="ayID">
                        <input type="hidden" id="facAssignIDElem" name="facAssignID">
                        <!-- Select faculty dropdown -->
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selfac" class="form-label fw-bold">Select Faculty:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="upselfacElem" name="selFaculty" data-live-search="true" required>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                            $query = "SELECT * FROM faculty WHERE isActive=1";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['facultyID'] . '">' . $row['lname'] . ', ' . $row['fname'] .' '. $row['mname'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching faculty</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Day, Start Time, End Time -->
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label for="txtday" class="form-label fw-bold">Day:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select name="txtDay[]" id="uptxtdayElem" class="form-control selectpicker" multiple required>
                                        <option value="M">Monday</option>
                                        <option value="T">Tuesday</option>
                                        <option value="W">Wednesday</option>
                                        <option value="Th">Thursday</option>
                                        <option value="F">Friday</option>
                                        <option value="S">Saturday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="start-time" class="form-label fw-bold">Start Time:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    </div>
                                    <input type="time" class="form-control" id="upstart-timeElem" name="txtStartTime" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end-time" class="form-label fw-bold">End Time:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    </div>
                                    <input type="time" class="form-control" id="upend-timeElem" name="txtEndTime" required>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <!-- Form submission buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateSubjectBtn">
                            <i class="bi bi-save"></i> Update
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
<!--UPDATE FAC Modal Form FOR JHS-->
<div class="modal fade" id="UPDATESubjectModalJHS" tabindex="-1" role="dialog" aria-labelledby="UPDATESubjectModalJHSLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_faculty2.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Update Faculty Assignment</legend>
                        <!-- Hidden input fields to store various IDs -->
                        <input type="hidden" id="upcurriculumIDInputJHS" name="curriculumID">                        
                        <input type="hidden" id="gradelvlIDJHS" name="gradelvlID">
                        <input type="hidden" id="secIDJHS" name="secID">
                        <input type="hidden" id="secNameJHS" name="secName">

                        <input type="hidden" id="upsubjectIDassignJHS" name="subjectID">
                        <input type="hidden" id="upsubjectNameJHS" name="subjectName">
                        <input type="hidden" id="updeptIDJHS" name="deptID">
                        <input type="hidden" id="upay_IDJHS" name="ayID">
                        <input type="hidden" id="facAssignIDJHS" name="facAssignID">
                        <!-- Select faculty dropdown -->
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selfac" class="form-label fw-bold">Select Faculty:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="upselfacJHS" name="selFaculty" data-live-search="true" required>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                            $query = "SELECT * FROM faculty WHERE isActive=1";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['facultyID'] . '">' . $row['lname'] . ', ' . $row['fname'] .' '. $row['mname'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching faculty</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Day, Start Time, End Time -->
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label for="txtday" class="form-label fw-bold">Day:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select name="txtDay[]" id="uptxtdayJHS" class="form-control selectpicker" multiple required>
                                        <option value="M">Monday</option>
                                        <option value="T">Tuesday</option>
                                        <option value="W">Wednesday</option>
                                        <option value="Th">Thursday</option>
                                        <option value="F">Friday</option>
                                        <option value="S">Saturday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="start-time" class="form-label fw-bold">Start Time:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    </div>
                                    <input type="time" class="form-control" id="upstart-timeJHS" name="txtStartTime" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end-time" class="form-label fw-bold">End Time:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    </div>
                                    <input type="time" class="form-control" id="upend-timeJHS" name="txtEndTime" required>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <!-- Form submission buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateSubjectBtn">
                            <i class="bi bi-save"></i> Update
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
<!--UPDATE FAC Modal Form FOR SHS -->
<div class="modal fade" id="UPDATESubjectModal" tabindex="-1" role="dialog" aria-labelledby="UPDATESubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_faculty2.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Update Faculty</legend>
                        <?php foreach ($curriculum as $row): ?>
                            <?php endforeach;?>
                        <!-- Hidden input fields to store various IDs -->
                        <input type="hidden" id="curriculumIDSHS" name="curriculumID"> 
                        <input type="hidden" name="programID" value="<?php echo htmlspecialchars(isset($_GET['programID']) ? $_GET['programID'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo htmlspecialchars(isset($_GET['gradelvlID']) ? $_GET['gradelvlID'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="semID" value="<?php echo htmlspecialchars(isset($_GET['semID']) ? $_GET['semID'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="secID" value="<?php echo htmlspecialchars(isset($_GET['secID']) ? $_GET['secID'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="secName" value="<?php echo htmlspecialchars(isset($_GET['secName']) ? $_GET['secName'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" id="subjectIDSHS" name="subjectID">
                        <input type="hidden" id="subjectNameSHS" name="subjectName">
                        <input type="hidden" id="deptIDSHS" name="deptID" value="<?php echo htmlspecialchars(isset($_GET['deptID']) ? $_GET['deptID'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" id="ayIDSHS" name="ayID">
                        <input type="hidden" id="ayName" name="ayName" value="<?php echo $ayName?>">

                        
                        <!-- Select faculty dropdown -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selfac" class="form-label fw-bold">Select Faculty:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="selfacSHS" name="selFaculty" data-live-search="true" required>
                                        <option selected disabled value="">Select Faculty:</option>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                            $query = "SELECT * FROM faculty WHERE isActive=1";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['facultyID'] . '">' . ucwords(strtolower($row['lname'])) . ', ' . ucwords(strtolower($row['fname'])) .' '. ucwords(strtolower($row['mname'])) . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching faculty</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                            <label for="txtday" class="form-label fw-bold">Day: <span class="fw-normal">(Leave it if no updates are needed)</span></label>
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select name="txtDay[]" id="txtdayUpdate" class="form-control selectpicker" multiple>
                                        <option value="Mon">Monday</option>
                                        <option value="Tue">Tuesday</option>
                                        <option value="Wed">Wednesday</option>
                                        <option value="Thu">Thursday</option>
                                        <option value="Fri">Friday</option>
                                        <option value="Sat">Saturday</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Time Input Fields -->
                        <div id="timeUpdate" class="mb-3"></div>

                    </fieldset>
                    <!-- Form submission buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateSubjectBtn">
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
        const daySelect = document.getElementById('txtdayUpdate');
        const timeInputsContainer = document.getElementById('timeUpdate');

        // Function to create time input fields for each selected day
        function updateTimeInputs() {
            // Clear existing time inputs
            timeInputsContainer.innerHTML = '';

            // Get selected days
            const selectedDays = Array.from(daySelect.selectedOptions).map(option => option.value);

            // Create time input fields for each selected day
            selectedDays.forEach(day => {
                const div = document.createElement('div');
                div.className = 'row mb-3';
                div.innerHTML = `
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Start Time (${day}):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            </div>
                            <input type="time" class="form-control" name="txtStartTime[]" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">End Time (${day}):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            </div>
                            <input type="time" class="form-control" name="txtEndTime[]" required>
                        </div>
                    </div>
                `;
                timeInputsContainer.appendChild(div);
            });
        }

        // Event listener for changes in the day selection
        daySelect.addEventListener('change', updateTimeInputs);
    });
</script>

<script>
$(document).ready(function() { 
    $('.update-fac-btn').click(function() {
        var curriculumID = $(this).data('curriculum-id');
        var subjectID = $(this).data('subject-id');
        var programID = $(this).data('subject-program');
        var subjectname = $(this).data('subject-name');
        var typeID = $(this).data('subject-type');
        var levelID = $(this).data('subject-level');
        var semID = $(this).data('subject-sem');
        var facultyID = $(this).data('faculty-id');
        var schedule = $(this).data('schedule-time');
        var ayID = $(this).data('ay-id');
        var secID = $(this).data('sec-id');
        var ayID = $(this).data('ay-id');
        var facultyAssID = $(this).data('faculty-assign');
        var isActive = $(this).data('program-status');


        $('#curriculumIDSHS').val(curriculumID);
        $('#subjectIDSHS').val(subjectID);
        $('#subjectNameSHS').val(subjectname);
        $('#ayIDSHS').val(ayID);

        // Clear previous selections
        $('#selfacSHS').val(facultyID).trigger('change');


        if (isActive == 1) {
            $('#programStatus').prop('checked', true);
            $('#statusLabel').text('Active').css('color', '#1a237e'); 
        } else {
            $('#programStatus').prop('checked', false);
            $('#statusLabel').text('Inactive').css('color', '#d9534f'); 
        }

        $('#hiddenStatus').val(isActive);
        
    
        $('#programStatus').change(function() {
        if ($(this).is(':checked')) {
            $('#hiddenStatus').val(1); //
            $('#statusLabel').text('Active').css('color', '#1a237e');
        } else {
            $('#hiddenStatus').val(0);
            $('#statusLabel').text('Inactive').css('color', '#d9534f');
        }
});
    });
    $('.update-fac-btn-jhs').click(function() {
        var curriculumID = $(this).data('curriculum-id');
        var subjectID = $(this).data('subject-id');
        var programID = $(this).data('subject-program');
        var subjectname = $(this).data('subject-name');
        var typeID = $(this).data('subject-type');
        var levelID = $(this).data('subject-level');
        var semID = $(this).data('subject-sem');
        var facultyID = $(this).data('faculty-id');
        var schedule = $(this).data('schedule-time');
        var ayID = $(this).data('ay-id');
        var secID = $(this).data('sec-id');
        var ayID = $(this).data('ay-id');
        var facultyAssID = $(this).data('faculty-assign');

        $('#curriculumIDSHS').val(curriculumID);
        $('#subjectIDSHS').val(subjectID);
        $('#subjectNameSHS').val(subjectname);
        $('#ayIDSHS').val(ayID);

        // Clear previous selections
        $('#selfacSHS').val(facultyID).trigger('change');

    
    });
});


</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    $('#addSubjectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var currID = button.data('curriculum-id'); 
        $('#curriculumIDInput').val(currID); 
    });


    $('#addSubjectModal').on('hidden.bs.modal', function () {
        $('#curriculumIDInput').val('');
    });

    $('form.needs-validation').on('submit', function (event) {
        var form = $(this)[0];
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    
});

$(document).ready(function() {
    $('.fac-btn').click(function(){
        var subjectID = $(this).data('subject-id');
        var subjectName = $(this).data('subject-name');
        var deptID = $(this).data('dept-id');
        var ayID = $(this).data('ay-id');
        

        // Populate the modal inputs
        $('#subjectIDassign').val(subjectID);
        $('#subjectName').val(subjectName);
        $('#deptID').val(deptID);
        $('#ay_ID').val(ayID);

    });
$(document).ready(function() {
    // When the modal is triggered, set the necessary values
    $(document).ready(function() {
    // Initialize Select Picker
    $('.selectpicker').selectpicker();

    // Add event listeners and functions
    $('#addSubjectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var currID = button.data('curriculum-id'); 
        $('#curriculumIDInput').val(currID); 
    });

    $('#addSubjectModal').on('hidden.bs.modal', function () {
        $('#curriculumIDInput').val('');
    });

    $('form.needs-validation').on('submit', function (event) {
        var form = $(this)[0];
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    $('.fac-btn').click(function(){
        var subjectID = $(this).data('subject-id');
        var subjectName = $(this).data('subject-name');
        var deptID = $(this).data('dept-id');
        var ayID = $(this).data('ay-id');
        
        // Populate the modal inputs
        $('#subjectIDassign').val(subjectID);
        $('#subjectName').val(subjectName);
        $('#deptID').val(deptID);
        $('#ay_ID').val(ayID);

        // Refresh Select Picker
        $('.selectpicker').selectpicker('refresh');
    });

    $('.update-fac-btn-elem').click(function(){
        var curriculumID = $(this).data('curriculum-id');
        var subjectID = $(this).data('subject-id');
        var subjectName = $(this).data('subject-name');
        var deptID = $(this).data('dept-id');
        var ayID = $(this).data('ay-id');
        var facultyID = $(this).data('faculty-id');
        var startTime = $(this).data('start-time');
        var endTime = $(this).data('end-time');
        var faID = $(this).data('faculty-assign');

        var gradelvlID = $(this).data('gradelvl-id');
        var secID = $(this).data('sec-id');
        var secName = $(this).data('sec-name');

        var day = $(this).data('subject-day').split(',');

        // Populate the modal inputs
        $('#upcurriculumIDInputElem').val(curriculumID);
        $('#upsubjectIDassignElem').val(subjectID);
        $('#upsubjectNameElem').val(subjectName);
        $('#updeptIDElem').val(deptID);
        $('#upay_IDElem').val(ayID);
        $('#facAssignIDElem').val(faID);

        $('#secNameElem').val(secName);
        $('#secIDElem').val(secID);
        $('#gradelvlIDElem').val(gradelvlID);

        // Set the faculty dropdown value and refresh selectpicker
        $('#upselfacElem').val(facultyID).selectpicker('refresh');

        // Set the multiple day selection and refresh selectpicker
        $('#uptxtdayElem').val(day).selectpicker('refresh');

        // Set the start and end times
        $('#upstart-timeElem').val(startTime);
        $('#upend-timeElem').val(endTime);
    });
    $('.update-fac-btn-jhs').click(function(){
        var curriculumID = $(this).data('curriculum-id');
        var subjectID = $(this).data('subject-id');
        var subjectName = $(this).data('subject-name');
        var deptID = $(this).data('dept-id');
        var ayID = $(this).data('ay-id');
        var facultyID = $(this).data('faculty-id');
        var startTime = $(this).data('start-time');
        var endTime = $(this).data('end-time');
        var faID = $(this).data('faculty-assign');

        var gradelvlID = $(this).data('gradelvl-id');
        var secID = $(this).data('sec-id');
        var secName = $(this).data('sec-name');

        var day = $(this).data('subject-day').split(',');

        // Populate the modal inputs
        $('#upcurriculumIDInputJHS').val(curriculumID);
        $('#upsubjectIDassignJHS').val(subjectID);
        $('#upsubjectNameJHS').val(subjectName);
        $('#updeptIDJHS').val(deptID);
        $('#upay_IDJHS').val(ayID);
        $('#facAssignIDJHS').val(faID);

        $('#secNameJHS').val(secName);
        $('#secIDJHS').val(secID);
        $('#gradelvlIDJHS').val(gradelvlID);

        // Set the faculty dropdown value and refresh selectpicker
        $('#upselfacJHS').val(facultyID).selectpicker('refresh');

        // Set the multiple day selection and refresh selectpicker
        $('#uptxtdayJHS').val(day).selectpicker('refresh');

        // Set the start and end times
        $('#upstart-timeJHS').val(startTime);
        $('#upend-timeJHS').val(endTime);
    });
    
    // Form validation before submission
    $('form.needs-validation').on('submit', function(event) {
        var form = $(this)[0];
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});

    // Form validation before submission
    $('form.needs-validation').on('submit', function(event) {
        var form = $(this)[0];
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});

});



</script>

