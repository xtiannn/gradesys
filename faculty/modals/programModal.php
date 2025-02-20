
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
$(document).ready(function(){
    $('.update-btn').click(function(){
        var programID = $(this).data('program-id');
        var name = $(this).data('program-name');
        var code = $(this).data('program-code');
    
        $('#programID').val(programID);
        $('#updatecode').val(code); 
        $('#updatename').val(name); 
    });
});

</script>

<script>
    // Function to handle delete button click
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
                // Send AJAX request to delete_program.php
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_program.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                // Display success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'The program has been deleted successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    // Remove the table row
                                    button.closest('tr').remove();
                                });
                            } else {
                                // Display error message if deletion fails
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Failed to delete the program: ' + response.message
                                });
                            }
                        } else {
                            // Display error message if request fails
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to delete the program. Please try again later.'
                            });
                        }
                    }
                };
                xhr.send('programId=' + programId); // Send the program ID to the server
            }
        });
    }

    // Attach event listener to delete buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default form submission behavior
                var programId = this.getAttribute('data-program-id');
                var programName = this.closest('tr').querySelector('td:nth-child(3)').innerText; // Get the program name from the row
                handleDeleteButtonClick(programId, programName, this);
            });
        });
    });
</script>