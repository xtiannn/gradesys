
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

<style>
    .form-check-input {
        width: 40px;
        height: 20px;
    }

    .form-check-input:checked {
        background-color: #1a237e;
    }

    .form-check-label {
        font-weight: bold;
        padding-left: 10px;
        color: #1a237e; 
    }

    .form-check-input:not(:checked) + .form-check-label {
        color: #d9534f;
    }
</style>
<!-- Modal Form -->
<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="addProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_program.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Create Program</legend>
                            <div class="col-md-12 mb-3">
                                <label for="programcode" class="form-label fw-bold">Program Code:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-code"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="programcode" name="txtProgramCode" placeholder="Program Code">
                                </div>
                            </div>
                        <div class="col-md-12 mb-3">
                            <label for="programname" class="form-label fw-bold">Program Title:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-journal-code"></i></span>
                                </div>
                                <input type="text" class="form-control" id="programname" name="txtProgramName" placeholder="Program Title">
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveProgramBtn">
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
<div class="modal fade" id="updateProgramModal" tabindex="-1" role="dialog" aria-labelledby="addProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_program.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <input type="hidden" id="programID" name="programID">
                        <legend class="mb-4">Update Program</legend>
                            <div class="row mb-2">
                                <div class="col-md-12 mb-3">
                                    <label for="updatecode" class="form-label fw-bold">Program Code:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-code"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="updatecode" name="txtProgramCode" placeholder="Program Code">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="updatename" class="form-label fw-bold">Program Title:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal-code"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="updatename" name="txtProgramName" placeholder="Program Title">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="programStatus" class="form-label fw-bold">Program Status:</label>
                                <div class="form-check form-switch" style="margin-left: 20px;"> 
                                    <input class="form-check-input" type="checkbox" id="programStatus" >
                                    <label class="form-check-label" for="programStatus" id="statusLabel">Active</label>
                                </div>
                            </div>

                            <input type="hidden" id="hiddenStatus" name="status">

                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateProgramBtn">
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
    // Handle the click event for the update button
    $('.update-btn').click(function() {
        var programID = $(this).data('program-id');
        var name = $(this).data('program-name');
        var code = $(this).data('program-code');
        var isActive = $(this).data('program-status');  // This will be either 1 or 0 (active or inactive)

        // Populate the modal form fields
        $('#programID').val(programID);
        $('#updatecode').val(code);
        $('#updatename').val(name);

        // Dynamically set the switch state based on isActive value
        if (isActive == 1) {
            $('#programStatus').prop('checked', true); // Check the switch if active
            $('#statusLabel').text('Active').css('color', '#1a237e'); // Change label to Active and set color to blue
        } else {
            $('#programStatus').prop('checked', false); 
            $('#statusLabel').text('Inactive').css('color', '#d9534f'); 
        }

        // Update the hidden status field based on the checkbox state
        var hiddenStatus = $('#programStatus').prop('checked') ? 1 : 0;
        $('#hiddenStatus').val(hiddenStatus);
    });

    // Ensure the hidden field is correctly set when submitting the form
    $('form').submit(function() {
        var hiddenStatus = $('#programStatus').prop('checked') ? 1 : 0;
        $('#hiddenStatus').val(hiddenStatus);
    });

    document.getElementById('programStatus').addEventListener('change', function() {
        var label = document.getElementById('statusLabel');
        if (this.checked) {
            label.textContent = 'Active';
            label.style.color = '#1a237e'; // Navy Blue for active
        } else {
            label.textContent = 'Inactive';
            label.style.color = '#d9534f'; // Red for inactive
        }
    });
});

</script>


<script>
    function handleDeleteButtonClick(programId, programName, button) {
        Swal.fire({
            title: 'Confirmation Required',
            text: 'You are about to delete the program: ' + programName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_program.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'The program has been deleted successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    button.closest('tr').remove();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Deletion Failed!',
                                    text: response.message
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to delete the program. Please try again later.'
                            });
                        }
                    }
                };
                xhr.send('programId=' + programId); 
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); 
                var programId = this.getAttribute('data-program-id');
                var programName = this.closest('tr').querySelector('td:nth-child(3)').innerText; 
                handleDeleteButtonClick(programId, programName, this);
            });
        });
    });
</script>
