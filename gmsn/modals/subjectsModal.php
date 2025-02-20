

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
                <form id="addSubjectForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Create Subject</legend>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selprog" class="form-label fw-bold">Program:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal-code"></i></span>
                                </div>
                                <select class="form-select form-control selectpicker" id="selprog" name="selProg[]" multiple required>
                                    <option disabled value="">Select Program</option>
                                    <?php
                                        include("../includes/config.php");
                                        try {
                                            $sql = "SELECT p.programID, p.programcode, p.programname, d.deptID, d.deptName 
                                            FROM programs p
                                            LEFT JOIN department d ON d.deptID = p.deptID
                                            WHERE p.isActive = 1";
                                    
                                            $stmt = $conn->query($sql); 
                                            
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['programID'] . '">' . $row['programcode'] . ' - ' . $row['programname'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching programs</option>';
                                        }         
                                    ?>  
                                </select>

                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subjectcode" class="form-label fw-bold">Subject Code:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-code"></i></span>
                                    </div>
                                    <input type="hidden" id="subjectIDInput" name="txtSubjectID">
                                    <input type="text" class="form-control" id="subjectcode" name="txtSubjectCode" placeholder="Subject Code" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="subjectname" class="form-label fw-bold">Subject Title:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="subjectname" name="txtSubjectName" placeholder="Title" required>
                                </div>
                            </div>
                        </div>
                    </fieldset>

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




<!-- update Form -->
<div class="modal fade" id="updateSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form id="updateSubjectForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                    <input type="hidden" name="subjectID" id="subject_id">
                        <legend class="mb-4">Update Subject</legend>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="updateprogram" class="form-label fw-bold">Program:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal-code"></i></span>
                                </div>
                                    <select class="form-select form-control selectpicker" id="updateprogram" name="selProg[]" multiple>
                                    <?php
                                        include("../includes/config.php");
                                        try {
                                            $sql = "SELECT * FROM programs";
                                            $stmt = $conn->query($sql); 
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['programID'] . '">' . $row['programcode']. '</option>';
                                            }
                                        } catch (\Throwable $th) {
                                            echo '<option disabled>Error fetching programs</option>';
                                        }         
                                    ?>  
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subjectcodeUpdate" class="form-label fw-bold">Subject Code:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-code"></i></span>
                                    </div>
                                    <input type="hidden" id="subjectIDInput" name="txtSubjectID">
                                    <input type="text" class="form-control" id="subjectcodeUpdate" name="txtSubjectCode" placeholder="Subject Code" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="subjectnameUpdate" class="form-label fw-bold">Subject Title:</label>
                                <div class="input-group">
                                        <?php 
                                            require_once("includes/config.php");
                                            $query = "SELECT subjectname
                                            FROM subjects";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                            $count = 0;
                                            foreach ($subjects as $subject):
                                                endforeach;
                                        ?>
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="subjectnameUpdate" name="txtSubjectName" placeholder="Title"  required>
                                </div>
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="programStatus" class="form-label fw-bold">Status:</label>
                            <div class="form-check form-switch" style="margin-left: 20px;"> 
                                <input class="form-check-input" type="checkbox" id="programStatus" >
                                <label class="form-check-label" for="programStatus" id="statusLabel">Active</label>
                            </div>
                        </div>

                        <input type="hidden" id="hiddenStatus" name="status">
                    </fieldset>

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

<script>
$(document).ready(function() {

    // Handle button click to fill form fields
    $('.update-btn').click(function() {
        var subjectID = $(this).data('subject-id');
        var subjectname = $(this).data('subject-name');
        var subjectcode = $(this).data('subject-code');
        var programIDs = $(this).data('subject-program').toString().split(',');
        var isActive = $(this).data('program-status');


        $('#subject_id').val(subjectID);
        $('#subjectnameUpdate').val(subjectname);
        $('#subjectcodeUpdate').val(subjectcode);

        $('#updateprogram').val(programIDs).trigger('change');


        if (isActive == 1) {
            $('#programStatus').prop('checked', true);
            $('#statusLabel').text('Active').css('color', '#1a237e'); 
        } else {
            $('#programStatus').prop('checked', false);
            $('#statusLabel').text('Inactive').css('color', '#d9534f'); 
        }

    $('#hiddenStatus').val(isActive);
    });

    $('#programStatus').change(function() {
        if ($(this).is(':checked')) {
            $('#hiddenStatus').val(1); //
            $('#statusLabel').text('Active').css('color', '#1a237e');
        } else {
            $('#hiddenStatus').val(0);
            $('#statusLabel').text('Inactive').css('color', '#d9534f');
        }
    });

    // Handle form submission
    $('#updateSubjectForm').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = {
            updateSubjectBtn: true,
            txtSubjectCode: $('#subjectcodeUpdate').val(),
            txtSubjectName: $('#subjectnameUpdate').val(),
            selProg: $('#updateprogram').val(),
            subjectID: $('#subject_id').val(),
            status: $('#hiddenStatus').val()
        };

        $.ajax({
            url: 'save_subject.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'The Subject has been updated successfully',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        // Reload the page
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: result.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to process the request.'
                });
            }
        });
    });
    $('#addSubjectForm').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Collect the values from the input fields
    var subjectCode = $('#subjectcode').val().trim();
    var subjectName = $('#subjectname').val().trim();
    var selectedProgram = $('#selprog').val();

    // Simple validation check for empty fields
    if (!subjectCode || !subjectName || !selectedProgram) {
        // Show a warning if any field is empty
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: 'Please fill out all required fields.',
            showConfirmButton: true
        });
        return; // Stop the function if validation fails
    }

    // Prepare the form data
    var formData = {
        saveSubjectBtn: true,
        txtSubjectCode: subjectCode,
        txtSubjectName: subjectName,
        selProg: selectedProgram
    };

    // Make the AJAX request
    $.ajax({
        url: 'save_subject.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            var result = JSON.parse(response);
            if (result.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: result.message,
                    showConfirmButton: true,
                    timer: 5000
                }).then(() => {
                    // Reload the page
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: result.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to process the request.'
            });
        }
    });
});


});
</script>


